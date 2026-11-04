<?php
/**
 * Controller de um pedido específico (RF004 e RF005).
 *
 * Rotas:
 *   GET  pedido.php?id=1                                -> mostra o pedido
 *   POST acao=adicionar + pedido_id, item_id, quantidade -> adiciona item (RF004)
 *   POST acao=remover   + pedido_id, item_id, quantidade -> remove item (RF004)
 *
 * Variáveis enviadas para a view: $pedido, $itensPedido, $total,
 * $itensCardapio, $erros, $mensagem.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/ItemCardapio.php';

$model = new Pedido();
$modelCardapio = new ItemCardapio();

// Valores padrão para a view (sempre definidos)
$pedido = null;
$itensPedido = [];
$total = 0.0;
$itensCardapio = $modelCardapio->listar(); // opções do select de itens
$erros = [];
$mensagem = '';

// Mensagens de sucesso exibidas depois do redirect
$mensagensSucesso = [
    'aberto'     => 'Pedido aberto com sucesso.',
    'adicionado' => 'Item adicionado ao pedido.',
    'removido'   => 'Item removido do pedido.',
];

$acao = $_POST['acao'] ?? '';
$id = $_POST['pedido_id'] ?? $_GET['id'] ?? '';

// ---------- Requisições POST: adicionar ou remover itens (RF004) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id'] ?? '';
    $quantidade = $_POST['quantidade'] ?? '';

    if ($acao === 'adicionar') {
        $erros = $model->adicionarItem($id, $itemId, $quantidade);
        $sucesso = 'adicionado';
    } elseif ($acao === 'remover') {
        $erros = $model->removerItem($id, $itemId, $quantidade);
        $sucesso = 'removido';
    } else {
        $erros = ['Ação inválida.'];
    }

    // Deu certo: volta para a tela do pedido (padrão POST -> redirect -> GET)
    if (empty($erros)) {
        header('Location: pedido.php?id=' . urlencode($id) . '&sucesso=' . $sucesso);
        exit;
    }
}

// TODO Sprint 3 — RF006/RF008: alterar status e cancelar o pedido

// ---------- Exibir o pedido ----------
$pedido = $model->buscarPorId($id);

if ($pedido === null) {
    http_response_code(404);
    $erros = ['Pedido não encontrado.'];
} else {
    $itensPedido = $pedido['itens'];
    $total = $model->calcularTotal($id);

    $sucesso = $_GET['sucesso'] ?? '';
    if (empty($erros) && isset($mensagensSucesso[$sucesso])) {
        $mensagem = $mensagensSucesso[$sucesso];
    }
}

include __DIR__ . '/../views/pedidos/detalhe.php';
