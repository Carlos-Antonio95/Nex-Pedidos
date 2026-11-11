<?php
/**
 * Topo das telas de acesso (login e cadastro), sem menu lateral.
 * Usa $titulo, definido pela view que inclui este arquivo.
 */
if (!function_exists('e')) {
    function e($valor)
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

$titulo = $titulo ?? 'Acesso';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f7fb">
    <title><?= e($titulo) ?> · Nex-Pedidos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilo.css">
</head>
<body class="auth-body">
<main class="auth-shell">
    <a class="brand auth-brand" href="login.php" aria-label="Nex-Pedidos">
        <span class="brand-mark" aria-hidden="true">N<span>×</span></span>
        <span class="brand-name">Nex<span>-</span>Pedidos<small>GESTÃO DE PEDIDOS</small></span>
    </a>
