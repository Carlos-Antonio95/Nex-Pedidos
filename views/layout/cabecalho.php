<?php
if (!function_exists('e')) {
    function e($valor)
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

$paginaAtual = $paginaAtual ?? 'pedidos';
$titulo = $titulo ?? 'Nex-Pedidos';
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
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="pedidos.php" aria-label="Nex-Pedidos, início">
            <span class="brand-mark" aria-hidden="true">N<span>×</span></span>
            <span class="brand-name">Nex<span>-</span>Pedidos<small>GESTÃO DE PEDIDOS</small></span>
        </a>
        <div class="side-label">MENU PRINCIPAL</div>
        <nav class="main-nav" aria-label="Menu principal">
            <a class="nav-link<?= $paginaAtual === 'pedidos' ? ' active' : '' ?>" href="pedidos.php">
                <span class="nav-icon" aria-hidden="true">▦</span> Pedidos
            </a>
            <a class="nav-link<?= $paginaAtual === 'cardapio' ? ' active' : '' ?>" href="cardapio.php">
                <span class="nav-icon" aria-hidden="true">◈</span> Cardápio
            </a>
        </nav>
        <div class="sidebar-bottom">
            <div class="open-status">
                <span class="status-pulse" aria-hidden="true"></span>
                <span>Operação ativa<small>Gerencie seus pedidos e itens</small></span>
            </div>
            <div class="user-card">
                <span class="avatar" aria-hidden="true">NP</span>
                <span>Nex-Pedidos<small>Gestão do restaurante</small></span>
            </div>
        </div>
    </aside>
    <main class="main-content">
        <header class="topbar">
            <div class="breadcrumb">Painel <span>/</span> <strong><?= e($titulo) ?></strong></div>
            <div class="topbar-right">
                <span><?= e(date('d/m/Y')) ?></span>
                <span class="top-avatar" aria-hidden="true">NP</span>
            </div>
        </header>
