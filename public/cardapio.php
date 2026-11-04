<?php
/**
 * Controller do cardápio (Sprint 1 — RF001 e RF002).
 *
 * Rotas:
 *   GET  cardapio.php                     -> lista os itens
 *   GET  cardapio.php?acao=cadastrar      -> formulário vazio
 *   GET  cardapio.php?acao=editar&id=1    -> formulário preenchido
 *   POST acao=cadastrar                   -> salva novo item
 *   POST acao=editar + id                 -> salva alterações
 *   POST acao=excluir + id                -> exclui o item
 *
 * Variáveis enviadas para as views: $itens, $item, $erros, $mensagem.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ItemCardapio.php';

$model = new ItemCardapio();

// Valores padrão para as views (sempre definidos)
$itens = [];
$item = ['id' => '', 'nome' => '', 'descricao' => '', 'preco' => '', 'categoria' => ''];
$erros = [];
$mensagem = '';

// Mensagens de sucesso exibidas depois do redirect
$mensagensSucesso = [
    'cadastrado' => 'Item cadastrado com sucesso.',
    'editado'    => 'Item editado com sucesso.',
    'excluido'   => 'Item excluído com sucesso.',
];

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';
$id = $_POST['id'] ?? $_GET['id'] ?? '';

// ---------- Requisições POST: salvar ou excluir ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados digitados no formulário
    $dados = [
        'nome'      => $_POST['nome'] ?? '',
        'descricao' => $_POST['descricao'] ?? '',
        'preco'     => $_POST['preco'] ?? '',
        'categoria' => $_POST['categoria'] ?? '',
    ];

    if ($acao === 'cadastrar') {
        $erros = $model->cadastrar($dados);
        $sucesso = 'cadastrado';
    } elseif ($acao === 'editar') {
        $erros = $model->editar($id, $dados);
        $sucesso = 'editado';
    } elseif ($acao === 'excluir') {
        $erros = $model->excluir($id);
        $sucesso = 'excluido';
    } else {
        $erros = ['Ação inválida.'];
    }

    // Deu certo: redireciona para a listagem (padrão POST -> redirect -> GET)
    if (empty($erros)) {
        header('Location: cardapio.php?sucesso=' . $sucesso);
        exit;
    }

    // Deu erro ao salvar: volta para o formulário com o que foi digitado
    if ($acao === 'cadastrar' || ($acao === 'editar' && $model->buscarPorId($id) !== null)) {
        $item = $dados;
        $item['id'] = ($acao === 'editar') ? $id : '';
        include __DIR__ . '/../views/cardapio/form.php';
        exit;
    }

    // Erro ao excluir (ou item não encontrado): mostra a listagem com o erro
    $itens = $model->listar();
    include __DIR__ . '/../views/cardapio/lista.php';
    exit;
}

// ---------- Requisições GET: exibir telas ----------
if ($acao === 'cadastrar') {
    include __DIR__ . '/../views/cardapio/form.php';
    exit;
}

if ($acao === 'editar') {
    $encontrado = $model->buscarPorId($id);

    if ($encontrado !== null) {
        $item = $encontrado;
        include __DIR__ . '/../views/cardapio/form.php';
        exit;
    }

    $erros[] = 'Item não encontrado.';
}

// Listagem (tela padrão)
$sucesso = $_GET['sucesso'] ?? '';
if (isset($mensagensSucesso[$sucesso])) {
    $mensagem = $mensagensSucesso[$sucesso];
}

$itens = $model->listar();
include __DIR__ . '/../views/cardapio/lista.php';
