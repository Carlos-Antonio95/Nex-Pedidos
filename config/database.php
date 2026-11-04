<?php
/**
 * Conexão com o banco de dados SQLite.
 *
 * Use a função conexao() em qualquer lugar do sistema para obter
 * a conexão PDO. A conexão é criada uma única vez por requisição.
 * Na primeira execução, as tabelas são criadas e recebem dados de
 * exemplo: alguns itens no cardápio e um pedido em cada status.
 */

function conexao()
{
    // "static" faz a variável manter o valor entre as chamadas da função
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO('sqlite:' . caminhoDoBanco());
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // No SQLite as chaves estrangeiras vêm desligadas por padrão
        $pdo->exec('PRAGMA foreign_keys = ON');

        criarTabelas($pdo);
        inserirDadosDeExemplo($pdo);
    }

    return $pdo;
}

/**
 * Retorna o caminho do arquivo .sqlite.
 *
 * Por padrão é database/pedidos.sqlite. Se a variável de ambiente DB_PATH
 * estiver definida, usa esse caminho (útil para testes em um banco separado).
 * Caminhos relativos são contados a partir da raiz do projeto.
 */
function caminhoDoBanco()
{
    $raiz = __DIR__ . '/..';
    $caminho = getenv('DB_PATH');

    if ($caminho === false || trim($caminho) === '') {
        return $raiz . '/database/pedidos.sqlite';
    }

    // Caminho absoluto: "/..." no Linux/Mac ou "C:\..." no Windows
    if (preg_match('#^([a-zA-Z]:)?[/\\\\]#', $caminho)) {
        return $caminho;
    }

    return $raiz . '/' . $caminho;
}

/**
 * Cria as tabelas do sistema, caso ainda não existam.
 */
function criarTabelas(PDO $pdo)
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS itens_cardapio (
            id        INTEGER PRIMARY KEY AUTOINCREMENT,
            nome      TEXT NOT NULL,
            descricao TEXT,
            preco     REAL NOT NULL CHECK (preco > 0),
            categoria TEXT NOT NULL
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pedidos (
            id        INTEGER PRIMARY KEY AUTOINCREMENT,
            mesa      INTEGER,
            cliente   TEXT,
            status    TEXT NOT NULL DEFAULT 'Recebido'
                      CHECK (status IN ('Recebido', 'Em preparo', 'Pronto', 'Entregue', 'Cancelado')),
            criado_em TEXT NOT NULL DEFAULT (datetime('now', 'localtime')),
            -- O pedido precisa ter uma mesa ou o nome do cliente
            CHECK (mesa IS NOT NULL OR (cliente IS NOT NULL AND cliente <> ''))
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS itens_pedido (
            id             INTEGER PRIMARY KEY AUTOINCREMENT,
            pedido_id      INTEGER NOT NULL,
            item_id        INTEGER NOT NULL,
            quantidade     INTEGER NOT NULL CHECK (quantidade > 0),
            preco_unitario REAL NOT NULL,
            FOREIGN KEY (pedido_id) REFERENCES pedidos (id),
            FOREIGN KEY (item_id) REFERENCES itens_cardapio (id)
        )
    ");
}

/**
 * Insere dados de exemplo nas tabelas que estiverem vazias.
 */
function inserirDadosDeExemplo(PDO $pdo)
{
    inserirCardapioDeExemplo($pdo);
    inserirPedidosDeExemplo($pdo);
}

/**
 * Insere itens de exemplo se o cardápio estiver vazio.
 */
function inserirCardapioDeExemplo(PDO $pdo)
{
    $total = $pdo->query('SELECT COUNT(*) FROM itens_cardapio')->fetchColumn();

    if ($total > 0) {
        return;
    }

    $exemplos = [
        ['Suco de laranja', 8.50, 'Bebidas'],
        ['Hambúrguer', 20.00, 'Lanches'],
        ['Suco', 8.00, 'Bebidas'],
    ];

    $stmt = $pdo->prepare('INSERT INTO itens_cardapio (nome, preco, categoria) VALUES (?, ?, ?)');

    $pdo->beginTransaction();
    foreach ($exemplos as $exemplo) {
        $stmt->execute($exemplo);
    }
    $pdo->commit();
}

/**
 * Se não houver pedidos, cria pedidos de exemplo com 1 ou 2 itens cada,
 * para facilitar os testes.
 *
 * Não há pedido "Entregue" de propósito: o cenário de teste do filtro
 * (RF007) espera que um banco novo não tenha pedidos entregues.
 */
function inserirPedidosDeExemplo(PDO $pdo)
{
    $total = $pdo->query('SELECT COUNT(*) FROM pedidos')->fetchColumn();

    if ($total > 0) {
        return;
    }

    // Usa os 3 primeiros itens do cardápio (normalmente os de exemplo)
    $itens = $pdo->query('SELECT id, preco FROM itens_cardapio ORDER BY id LIMIT 3')->fetchAll();

    if (count($itens) < 3) {
        return;
    }

    // [mesa, cliente, status, minutos atrás, [[posição do item, quantidade], ...]]
    $exemplos = [
        [1,    null,    'Recebido',   5,  [[1, 1], [0, 1]]],
        [2,    null,    'Em preparo', 15, [[1, 2]]],
        [null, 'Ana',   'Pronto',     25, [[1, 1], [2, 1]]],
        [null, 'Bruno', 'Cancelado',  60, [[1, 1]]],
    ];

    $stmtPedido = $pdo->prepare(
        "INSERT INTO pedidos (mesa, cliente, status, criado_em)
         VALUES (?, ?, ?, datetime('now', 'localtime', ?))"
    );
    $stmtItem = $pdo->prepare(
        'INSERT INTO itens_pedido (pedido_id, item_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)'
    );

    $pdo->beginTransaction();
    foreach ($exemplos as $exemplo) {
        list($mesa, $cliente, $status, $minutos, $itensDoPedido) = $exemplo;

        $stmtPedido->execute([$mesa, $cliente, $status, '-' . $minutos . ' minutes']);
        $pedidoId = $pdo->lastInsertId();

        foreach ($itensDoPedido as $itemDoPedido) {
            $item = $itens[$itemDoPedido[0]];
            $stmtItem->execute([$pedidoId, $item['id'], $itemDoPedido[1], $item['preco']]);
        }
    }
    $pdo->commit();
}
