<?php
/**
 * Controller da lista de pedidos.
 *
 * Rotas:
 *   GET  pedidos.php                  -> lista os pedidos e o formulário de novo pedido
 *   POST acao=abrir + mesa, cliente   -> abre um pedido (RF003)
 *
 * Variáveis enviadas para a view: $pedidos, $mesa, $cliente, $erros, $mensagem.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Pedido.php';

$model = new Pedido();

// Valores padrão para a view (sempre definidos)
$pedidos = []; // TODO Sprint 3 — RF007: listar pedidos com filtro por status
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

// Listagem (tela padrão). Em caso de erro, volta com os valores digitados.
include __DIR__ . '/../views/pedidos/lista.php';
