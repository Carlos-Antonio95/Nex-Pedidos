<?php
/**
 * Controller da lista de pedidos.
 *
 * Rotas:
 *   GET  pedidos.php                  -> lista todos os pedidos (RF007)
 *   GET  pedidos.php?status=Pronto    -> lista só os pedidos com esse status (RF007)
 *   POST acao=abrir + mesa, cliente   -> abre um pedido (RF003)
 *
 * Variáveis enviadas para a view: $pedidos, $statusFiltro, $listaStatus,
 * $mesa, $cliente, $erros, $mensagem.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Pedido.php';

$model = new Pedido();

// Valores padrão para a view (sempre definidos)
$pedidos = [];
$statusFiltro = trim($_GET['status'] ?? '');
$listaStatus = Pedido::STATUS; // opções do select de filtro
$mesa = '';
$cliente = '';
$erros = [];
$mensagem = '';

$acao = $_POST['acao'] ?? '';

// ---------- Requisições POST: abrir pedido (RF003) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($acao === 'abrir') {
        $mesa = $_POST['mesa'] ?? '';
        $cliente = $_POST['cliente'] ?? '';

        $resultado = $model->abrir($mesa, $cliente);
        $erros = $resultado['erros'];

        // Deu certo: vai para a tela do pedido criado (padrão POST -> redirect -> GET)
        if (empty($erros)) {
            header('Location: pedido.php?id=' . $resultado['id'] . '&sucesso=aberto');
            exit;
        }
    } else {
        $erros = ['Ação inválida.'];
    }
}

// ---------- Listagem (RF007) ----------
$resultado = $model->listar($statusFiltro);
$pedidos = $resultado['pedidos'];
$erros = array_merge($erros, $resultado['erros']);

// Só avisa que a lista está vazia se o filtro for válido
if (empty($pedidos) && empty($resultado['erros'])) {
    $mensagem = 'Nenhum pedido encontrado.';
}

// Em caso de erro ao abrir, a tela volta com os valores digitados
include __DIR__ . '/../views/pedidos/lista.php';
