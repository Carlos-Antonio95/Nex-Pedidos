<?php
/**
 * Controller do login (Sprint 4 — RF010).
 *
 * Rotas:
 *   GET  login.php                    -> formulário de login
 *   GET  login.php?sucesso=cadastrado -> formulário com aviso de cadastro feito
 *   POST email, senha                 -> autentica e vai para a tela inicial
 *
 * Variáveis enviadas para a view: $email, $erros, $mensagem.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

// Quem já está logado vai direto para a tela inicial
if (usuarioLogado() !== null) {
    header('Location: pedidos.php');
    exit;
}

// Valores padrão para a view (sempre definidos)
$email = '';
$erros = [];
$mensagem = '';

$mensagensSucesso = [
    'cadastrado' => 'Cadastro realizado com sucesso. Faça login para continuar.',
    'saiu'       => 'Você saiu do sistema.',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $resultado = (new Usuario())->autenticar($email, $senha);
    $erros = $resultado['erros'];

    // Deu certo: guarda na sessão e abre a tela inicial (lista de pedidos)
    if (empty($erros)) {
        entrar($resultado['usuario']);
        header('Location: pedidos.php');
        exit;
    }
} else {
    $sucesso = $_GET['sucesso'] ?? '';
    if (isset($mensagensSucesso[$sucesso])) {
        $mensagem = $mensagensSucesso[$sucesso];
    }
}

include __DIR__ . '/../views/usuarios/login.php';
