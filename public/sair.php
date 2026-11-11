<?php
/**
 * Encerra a sessão do usuário (RF010) e volta para o login.
 * Aceita só POST, como as outras ações que alteram estado.
 */

require_once __DIR__ . '/../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sair();
    header('Location: login.php?sucesso=saiu');
    exit;
}

header('Location: ' . (usuarioLogado() !== null ? 'pedidos.php' : 'login.php'));
exit;
