<?php
/**
 * Controller do cadastro de usuário (Sprint 4 — RF009).
 *
 * Rotas:
 *   GET  cadastro.php              -> formulário de cadastro
 *   POST nome, email, senha        -> cadastra e vai para o login
 *
 * Variáveis enviadas para a view: $usuario (nome, email), $erros.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

// Quem já está logado não precisa se cadastrar
if (usuarioLogado() !== null) {
    header('Location: pedidos.php');
    exit;
}

// Valores padrão para a view (sempre definidos). A senha nunca volta para a tela.
$usuario = ['nome' => '', 'email' => ''];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome'  => $_POST['nome'] ?? '',
        'email' => $_POST['email'] ?? '',
        'senha' => $_POST['senha'] ?? '',
    ];

    $erros = (new Usuario())->cadastrar($dados);

    // Deu certo: vai para o login com a mensagem de sucesso (POST -> redirect -> GET)
    if (empty($erros)) {
        header('Location: login.php?sucesso=cadastrado');
        exit;
    }

    // Deu erro: volta com o que foi digitado (menos a senha)
    $usuario = ['nome' => $dados['nome'], 'email' => $dados['email']];
}

include __DIR__ . '/../views/usuarios/cadastro.php';
