<?php
$titulo = 'Cadastro';
include __DIR__ . '/../layout/acesso_topo.php';
?>
<section class="auth-card">
    <p class="eyebrow">NOVO USUÁRIO</p>
    <h1>Criar conta</h1>
    <p class="page-subtitle">Preencha seus dados para ter acesso ao sistema.</p>

    <?php if (!empty($erros)): ?>
        <div class="notice notice-error" role="alert">
            <ul><?php foreach ($erros as $erro): ?><li><?= e($erro) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form method="post" action="cadastro.php">
        <label class="field-label" for="nome">Nome</label>
        <input class="text-input" id="nome" name="nome" type="text" autocomplete="name" value="<?= e($usuario['nome']) ?>" placeholder="Ex.: Maria Silva">

        <label class="field-label" for="email">E-mail</label>
        <input class="text-input" id="email" name="email" type="text" inputmode="email" autocomplete="email" value="<?= e($usuario['email']) ?>" placeholder="voce@email.com">

        <label class="field-label" for="senha">Senha <span class="form-hint">(mínimo 6 caracteres)</span></label>
        <input class="text-input" id="senha" name="senha" type="password" autocomplete="new-password">

        <button class="button button-primary auth-submit" type="submit">Cadastrar</button>
    </form>

    <p class="auth-switch">Já tem conta? <a class="text-link" href="login.php">Entrar</a></p>
</section>
<?php include __DIR__ . '/../layout/acesso_rodape.php'; ?>
