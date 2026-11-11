<?php
// Página inicial: quem está logado vai para a lista de pedidos; os demais, para o login
require_once __DIR__ . '/../config/auth.php';

header('Location: ' . (usuarioLogado() !== null ? 'pedidos.php' : 'login.php'));
exit;
