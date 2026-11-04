<?php
/**
 * Model dos itens do cardápio (Sprint 1 — RF001 e RF002).
 *
 * Os métodos que alteram dados (cadastrar, editar, excluir) retornam
 * um array de erros. Se o array vier vazio, a operação deu certo.
 */

require_once __DIR__ . '/../config/database.php';

class ItemCardapio
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conexao();
    }

    /**
     * Lista todos os itens, ordenados por categoria e nome.
     */
    public function listar()
    {
        $stmt = $this->pdo->query('SELECT * FROM itens_cardapio ORDER BY categoria, nome');
        return $stmt->fetchAll();
    }

    /**
     * Busca um item pelo ID. Retorna null se não existir.
     */
    public function buscarPorId($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM itens_cardapio WHERE id = ?');
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        return $item ? $item : null;
    }

    /**
     * RF001 — Cadastra um novo item no cardápio.
     */
    public function cadastrar($dados)
    {
        $erros = $this->validar($dados);
        if (!empty($erros)) {
            return $erros;
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO itens_cardapio (nome, descricao, preco, categoria) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            trim($dados['nome']),
            trim($dados['descricao'] ?? ''),
            $this->converterPreco($dados['preco']),
            trim($dados['categoria']),
        ]);

        return [];
    }

    /**
     * RF002 — Edita um item existente.
     */
    public function editar($id, $dados)
    {
        if ($this->buscarPorId($id) === null) {
            return ['Item não encontrado.'];
        }

        $erros = $this->validar($dados);
        if (!empty($erros)) {
            return $erros;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE itens_cardapio SET nome = ?, descricao = ?, preco = ?, categoria = ? WHERE id = ?'
        );
        $stmt->execute([
            trim($dados['nome']),
            trim($dados['descricao'] ?? ''),
            $this->converterPreco($dados['preco']),
            trim($dados['categoria']),
            $id,
        ]);

        return [];
    }

    /**
     * RF002 — Exclui um item do cardápio.
     */
    public function excluir($id)
    {
        if ($this->buscarPorId($id) === null) {
            return ['Item não encontrado.'];
        }

        try {
            $stmt = $this->pdo->prepare('DELETE FROM itens_cardapio WHERE id = ?');
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            // A chave estrangeira impede excluir itens que já estão em algum pedido
            return ['Não é possível excluir: o item já foi usado em pedidos.'];
        }

        return [];
    }

    /**
     * Valida os dados do formulário e retorna a lista de erros encontrados.
     */
    public function validar($dados)
    {
        $erros = [];

        if (trim($dados['nome'] ?? '') === '') {
            $erros[] = 'O nome é obrigatório.';
        }

        if (trim($dados['categoria'] ?? '') === '') {
            $erros[] = 'A categoria é obrigatória.';
        }

        // RN001 — o preço deve ser numérico e maior que zero
        $preco = $this->converterPreco($dados['preco'] ?? '');
        if (!is_numeric($preco) || $preco <= 0) {
            $erros[] = 'O preço deve ser maior que zero.';
        }

        return $erros;
    }

    /**
     * Aceita preço com vírgula ("8,50") trocando por ponto ("8.50").
     */
    private function converterPreco($preco)
    {
        return str_replace(',', '.', trim((string) $preco));
    }
}
