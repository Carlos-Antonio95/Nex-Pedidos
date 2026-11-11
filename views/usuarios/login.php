<?php
$titulo = 'Entrar';
include __DIR__ . '/../layout/acesso_topo.php';
?>
<section class="auth-card">
    <p class="eyebrow">ACESSO AO SISTEMA</p>
    <h1>Entrar</h1>
    <p class="page-subtitle">Informe seu e-mail e senha para acessar o painel.</p>

    <?php if ($mensagem !== ''): ?>
        <div class="notice notice-success" role="status"><?= e($mensagem) ?></div>
    <?php endif; ?>
    <?php if (!empty($erros)): ?>
        <div class="notice notice-error" role="alert">
            <ul><?php foreach ($erros as $erro): ?><li><?= e($erro) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label class="field-label" for="email">E-mail</label>
        <input class="text-input" id="email" name="email" type="text" inputmode="email" autocomplete="username" value="<?= e($email) ?>" placeholder="voce@email.com">

        <label class="field-label" for="senha">Senha</label>
        <input class="text-input" id="senha" name="senha" type="password" autocomplete="current-password">

        <button class="button button-primary auth-submit" type="submit">Entrar</button>
    </form>

    <p class="auth-switch">Ainda não tem conta? <a class="text-link" href="cadastro.php">Cadastre-se</a></p>
</section>
<?php include __DIR__ . '/../layout/acesso_rodape.php'; ?>
