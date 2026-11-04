<?php
/**
 * Model dos pedidos (Sprints 2 e 3).
 *
 * Assim como em ItemCardapio, os métodos que alteram dados retornam
 * um array de erros. Se o array vier vazio, a operação deu certo.
 * As exceções são abrir() e listar(), que também precisam devolver
 * dados: elas retornam um array com os dados e a chave 'erros'.
 */

require_once __DIR__ . '/../config/database.php';

class Pedido
{
    // Todos os status possíveis de um pedido
    const STATUS = ['Recebido', 'Em preparo', 'Pronto', 'Entregue', 'Cancelado'];

    // RN003 — etapas que o pedido percorre, nesta ordem
    const ETAPAS = ['Recebido', 'Em preparo', 'Pronto', 'Entregue'];

    private $pdo;

    public function __construct()
    {
        $this->pdo = conexao();
    }

    /**
     * RF003 — Abre um novo pedido para uma mesa ou um cliente.
     *
     * Retorna ['id' => id do pedido criado, 'erros' => []].
     * Se houver erro, 'id' vem null e 'erros' traz as mensagens.
     */
    public function abrir($mesa, $cliente)
    {
        $mesa = trim((string) $mesa);
        $cliente = trim((string) $cliente);
        $erros = [];

        if ($mesa === '' && $cliente === '') {
            $erros[] = 'Informe o número da mesa ou o nome do cliente.';
        } elseif ($mesa !== '' && !$this->ehInteiroPositivo($mesa)) {
            $erros[] = 'O número da mesa deve ser um número inteiro maior que zero.';
        }

        if (!empty($erros)) {
            return ['id' => null, 'erros' => $erros];
        }

        // Campos vazios são gravados como NULL ("05" vira a mesa 5)
        $stmt = $this->pdo->prepare('INSERT INTO pedidos (mesa, cliente, status) VALUES (?, ?, ?)');
        $stmt->execute([
            $mesa === '' ? null : (int) $mesa,
            $cliente === '' ? null : $cliente,
            'Recebido',
        ]);

        return ['id' => (int) $this->pdo->lastInsertId(), 'erros' => []];
    }

    /**
     * Busca um pedido pelo ID, junto com seus itens. Retorna null se não existir.
     *
     * Os itens ficam na chave 'itens'. Cada item tem: item_id, nome,
     * quantidade, preco_unitario e subtotal.
     */
    public function buscarPorId($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM pedidos WHERE id = ?');
        $stmt->execute([$id]);
        $pedido = $stmt->fetch();

        if (!$pedido) {
            return null;
        }

        $stmt = $this->pdo->prepare('
            SELECT ip.item_id, ic.nome, ip.quantidade, ip.preco_unitario,
                   ip.quantidade * ip.preco_unitario AS subtotal
            FROM itens_pedido ip
            JOIN itens_cardapio ic ON ic.id = ip.item_id
            WHERE ip.pedido_id = ?
            ORDER BY ic.nome
        ');
        $stmt->execute([$id]);
        $pedido['itens'] = $stmt->fetchAll();

        return $pedido;
    }

    /**
     * RF004 / RN002 — Adiciona um item ao pedido.
     * Se o item já estiver no pedido, soma a quantidade.
     */
    public function adicionarItem($pedidoId, $itemId, $quantidade)
    {
        $erros = $this->validarAlteracao($pedidoId, $quantidade);
        if (!empty($erros)) {
            return $erros;
        }

        $stmt = $this->pdo->prepare('SELECT preco FROM itens_cardapio WHERE id = ?');
        $stmt->execute([$itemId]);
        $preco = $stmt->fetchColumn();

        if ($preco === false) {
            return ['Item não encontrado.'];
        }

        $linha = $this->buscarItemDoPedido($pedidoId, $itemId);

        if ($linha) {
            $stmt = $this->pdo->prepare('UPDATE itens_pedido SET quantidade = quantidade + ? WHERE id = ?');
            $stmt->execute([(int) $quantidade, $linha['id']]);
        } else {
            // Grava o preço atual do cardápio: se o preço mudar depois,
            // o pedido continua com o valor da hora em que foi feito
            $stmt = $this->pdo->prepare(
                'INSERT INTO itens_pedido (pedido_id, item_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$pedidoId, $itemId, (int) $quantidade, $preco]);
        }

        return [];
    }

    /**
     * RF004 — Remove unidades de um item do pedido.
     * Se a quantidade chegar a zero, o item sai do pedido.
     */
    public function removerItem($pedidoId, $itemId, $quantidade)
    {
        $erros = $this->validarAlteracao($pedidoId, $quantidade);
        if (!empty($erros)) {
            return $erros;
        }

        $linha = $this->buscarItemDoPedido($pedidoId, $itemId);

        if (!$linha) {
            return ['Este item não está no pedido.'];
        }

        $quantidade = (int) $quantidade;

        if ($quantidade > $linha['quantidade']) {
            return ['Não é possível remover mais unidades do que existem no pedido.'];
        }

        if ($quantidade == $linha['quantidade']) {
            $stmt = $this->pdo->prepare('DELETE FROM itens_pedido WHERE id = ?');
            $stmt->execute([$linha['id']]);
        } else {
            $stmt = $this->pdo->prepare('UPDATE itens_pedido SET quantidade = quantidade - ? WHERE id = ?');
            $stmt->execute([$quantidade, $linha['id']]);
        }

        return [];
    }

    /**
     * RF005 — Calcula o total do pedido (soma de quantidade × preço unitário).
     */
    public function calcularTotal($pedidoId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT COALESCE(SUM(quantidade * preco_unitario), 0) FROM itens_pedido WHERE pedido_id = ?'
        );
        $stmt->execute([$pedidoId]);

        return round((float) $stmt->fetchColumn(), 2);
    }

    /**
     * RF006 / RN003 — Avança o pedido para a próxima etapa.
     * Recebido → Em preparo → Pronto → Entregue, sem pular nem voltar.
     */
    public function alterarStatus($pedidoId, $novoStatus)
    {
        $pedido = $this->buscarPorId($pedidoId);

        if ($pedido === null) {
            return ['Pedido não encontrado.'];
        }

        $erros = $this->validarMudancaDeStatus($pedido, $novoStatus);
        if (!empty($erros)) {
            return $erros;
        }

        $stmt = $this->pdo->prepare('UPDATE pedidos SET status = ? WHERE id = ?');
        $stmt->execute([$novoStatus, $pedidoId]);

        return [];
    }

    /**
     * Retorna a próxima etapa do pedido, ou null se não houver
     * (pedido entregue, cancelado ou status desconhecido).
     */
    public function proximoStatus($statusAtual)
    {
        $posicao = array_search($statusAtual, self::ETAPAS);

        if ($posicao === false || !isset(self::ETAPAS[$posicao + 1])) {
            return null;
        }

        return self::ETAPAS[$posicao + 1];
    }

    /**
     * RF007 — Lista os pedidos, do mais recente para o mais antigo,
     * com o total de cada um. Se $status vier preenchido, filtra por ele.
     *
     * Retorna ['pedidos' => lista, 'erros' => []].
     * Cada pedido tem: id, mesa, cliente, status, criado_em e total.
     */
    public function listar($status = null)
    {
        $status = trim((string) $status);

        if ($status !== '' && !in_array($status, self::STATUS)) {
            return ['pedidos' => [], 'erros' => ['Status inválido.']];
        }

        // LEFT JOIN para incluir também os pedidos ainda sem itens (total 0)
        $sql = '
            SELECT p.*, COALESCE(SUM(ip.quantidade * ip.preco_unitario), 0) AS total
            FROM pedidos p
            LEFT JOIN itens_pedido ip ON ip.pedido_id = p.id
        ';
        $parametros = [];

        if ($status !== '') {
            $sql .= ' WHERE p.status = ?';
            $parametros[] = $status;
        }

        $sql .= ' GROUP BY p.id ORDER BY p.criado_em DESC, p.id DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametros);
        $pedidos = $stmt->fetchAll();

        foreach ($pedidos as $i => $pedido) {
            $pedidos[$i]['total'] = round((float) $pedido['total'], 2);
        }

        return ['pedidos' => $pedidos, 'erros' => []];
    }

    /**
     * RF008 / RN004 — Cancela o pedido. Pedido entregue não pode ser cancelado.
     */
    public function cancelar($pedidoId)
    {
        $pedido = $this->buscarPorId($pedidoId);

        if ($pedido === null) {
            return ['Pedido não encontrado.'];
        }

        if ($pedido['status'] === 'Entregue') {
            return ['Não é possível cancelar um pedido já entregue.'];
        }

        if ($pedido['status'] === 'Cancelado') {
            return ['Este pedido já está cancelado.'];
        }

        $stmt = $this->pdo->prepare('UPDATE pedidos SET status = ? WHERE id = ?');
        $stmt->execute(['Cancelado', $pedidoId]);

        return [];
    }

    /**
     * RN003 — Confere se o pedido pode ir para o novo status.
     */
    private function validarMudancaDeStatus($pedido, $novoStatus)
    {
        if (!in_array($novoStatus, self::STATUS)) {
            return ['Status inválido.'];
        }

        if ($novoStatus === 'Cancelado') {
            return ['Para cancelar o pedido, use a opção Cancelar.'];
        }

        if (!$this->estaAberto($pedido)) {
            return ['Não é possível alterar um pedido entregue ou cancelado.'];
        }

        if ($novoStatus !== $this->proximoStatus($pedido['status'])) {
            return ['Não é possível pular etapas ou voltar para uma etapa anterior.'];
        }

        return [];
    }

    /**
     * Validações comuns a adicionar e remover itens:
     * quantidade válida (RN002) e pedido existente e aberto.
     */
    private function validarAlteracao($pedidoId, $quantidade)
    {
        if (!$this->ehInteiroPositivo($quantidade)) {
            return ['A quantidade deve ser um número inteiro maior que zero.'];
        }

        $pedido = $this->buscarPorId($pedidoId);

        if ($pedido === null) {
            return ['Pedido não encontrado.'];
        }

        if (!$this->estaAberto($pedido)) {
            return ['Não é possível alterar um pedido entregue ou cancelado.'];
        }

        return [];
    }

    /**
     * Um pedido está aberto enquanto não foi entregue nem cancelado.
     * Só pedidos abertos podem ter itens ou status alterados.
     */
    private function estaAberto($pedido)
    {
        return !in_array($pedido['status'], ['Entregue', 'Cancelado']);
    }

    /**
     * Busca a linha de itens_pedido de um item dentro de um pedido.
     */
    private function buscarItemDoPedido($pedidoId, $itemId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM itens_pedido WHERE pedido_id = ? AND item_id = ?');
        $stmt->execute([$pedidoId, $itemId]);

        return $stmt->fetch();
    }

    /**
     * Confere se o valor é um número inteiro maior que zero.
     * Aceita 3 ou "3"; rejeita 0, "-1", "1.5", "2,0" e "abc".
     */
    private function ehInteiroPositivo($valor)
    {
        $valor = trim((string) $valor);

        return ctype_digit($valor) && (int) $valor > 0;
    }
}
