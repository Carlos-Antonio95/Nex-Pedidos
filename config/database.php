<?php
/**
 * Conexão com o banco de dados SQLite.
 *
 * Use a função conexao() em qualquer lugar do sistema para obter
 * a conexão PDO. A conexão é criada uma única vez por requisição.
 * Na primeira execução, as tabelas são criadas e o cardápio recebe
 * alguns itens de exemplo.
 */

function conexao()
{
    // "static" faz a variável manter o valor entre as chamadas da função
    static $pdo = null;

    if ($pdo === null) {
        $caminho = __DIR__ . '/../database/pedidos.sqlite';

        $pdo = new PDO('sqlite:' . $caminho);
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
 * Insere itens de exemplo se o cardápio estiver vazio.
 */
function inserirDadosDeExemplo(PDO $pdo)
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
