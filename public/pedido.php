<?php
/**
 * Controller de um pedido específico (RF004, RF005, RF006 e RF008).
 *
 * Rotas:
 *   GET  pedido.php?id=1                                -> mostra o pedido
 *   POST acao=adicionar + pedido_id, item_id, quantidade -> adiciona item (RF004)
 *   POST acao=remover   + pedido_id, item_id, quantidade -> remove item (RF004)
 *   POST acao=status    + pedido_id, status             -> avança o status (RF006)
 *   POST acao=cancelar  + pedido_id                     -> cancela o pedido (RF008)
 *
 * Variáveis enviadas para a view: $pedido, $itensPedido, $total,
 * $itensCardapio, $proximoStatus, $erros, $mensagem.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/ItemCardapio.php';

// RF010 — só usuários logados acessam esta página
exigirLogin();

$model = new Pedido();
$modelCardapio = new ItemCardapio();

// Valores padrão para a view (sempre definidos)
$pedido = null;
$itensPedido = [];
$total = 0.0;
$itensCardapio = $modelCardapio->listar(); // opções do select de itens
$proximoStatus = null;
$erros = [];
$mensagem = '';

// Mensagens de sucesso exibidas depois do redirect
$mensagensSucesso = [
    'aberto'     => 'Pedido aberto com sucesso.',
    'adicionado' => 'Item adicionado ao pedido.',
    'removido'   => 'Item removido do pedido.',
    'status'     => 'Status do pedido atualizado.',
    'cancelado'  => 'Pedido cancelado.',
];

$acao = $_POST['acao'] ?? '';
$id = $_POST['pedido_id'] ?? $_GET['id'] ?? '';

// ---------- Requisições POST: itens, status e cancelamento ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id'] ?? '';
    $quantidade = $_POST['quantidade'] ?? '';

    if ($acao === 'adicionar') {
        $erros = $model->adicionarItem($id, $itemId, $quantidade);
        $sucesso = 'adicionado';
    } elseif ($acao === 'remover') {
        $erros = $model->removerItem($id, $itemId, $quantidade);
        $sucesso = 'removido';
    } elseif ($acao === 'status') {
        $erros = $model->alterarStatus($id, $_POST['status'] ?? '');
        $sucesso = 'status';
    } elseif ($acao === 'cancelar') {
        $erros = $model->cancelar($id);
        $sucesso = 'cancelado';
    } else {
        $erros = ['Ação inválida.'];
    }

    // Deu certo: volta para a tela do pedido (padrão POST -> redirect -> GET)
    if (empty($erros)) {
        header('Location: pedido.php?id=' . urlencode($id) . '&sucesso=' . $sucesso);
        exit;
    }
}

// ---------- Exibir o pedido ----------
$pedido = $model->buscarPorId($id);

if ($pedido === null) {
    http_response_code(404);
    $erros = ['Pedido não encontrado.'];
} else {
    $itensPedido = $pedido['itens'];
    $total = $model->calcularTotal($id);
    $proximoStatus = $model->proximoStatus($pedido['status']);

    $sucesso = $_GET['sucesso'] ?? '';
    if (empty($erros) && isset($mensagensSucesso[$sucesso])) {
        $mensagem = $mensagensSucesso[$sucesso];
    }
}

include __DIR__ . '/../views/pedidos/detalhe.php';
