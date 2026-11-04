<?php
$paginaAtual = 'cardapio';
$titulo = 'Cardápio';
include __DIR__ . '/../layout/cabecalho.php';
?>
<section class="view">
    <div class="page-heading">
        <div>
            <p class="eyebrow">SEUS PRODUTOS</p>
            <h1>Cardápio</h1>
            <p class="page-subtitle">Cadastre, edite e remova os itens oferecidos pelo restaurante.</p>
        </div>
        <a class="button button-primary" href="cardapio.php?acao=cadastrar"><span class="plus" aria-hidden="true">+</span> Adicionar item</a>
    </div>

    <?php if ($mensagem !== ''): ?>
        <div class="notice notice-success" role="status"><?= e($mensagem) ?></div>
    <?php endif; ?>
    <?php if (!empty($erros)): ?>
        <div class="notice notice-error" role="alert">
            <ul><?php foreach ($erros as $erro): ?><li><?= e($erro) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <div class="menu-toolbar">
        <div><strong><?= count($itens) ?> <?= count($itens) === 1 ? 'item' : 'itens' ?></strong><span> no seu cardápio</span></div>
    </div>
    <?php if (empty($itens)): ?>
        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true">◈</div>
            <h3>Seu cardápio está vazio</h3>
            <p>Adicione o primeiro item para começar a montar os pedidos.</p>
            <a class="button button-primary" href="cardapio.php?acao=cadastrar">Adicionar item</a>
        </div>
    <?php else: ?>
        <div class="menu-grid">
            <?php foreach ($itens as $i): ?>
                <article class="menu-card">
                    <div class="menu-card-top">
                        <span class="menu-category"><?= e($i['categoria']) ?></span>
                        <span class="menu-price">R$ <?= number_format((float) $i['preco'], 2, ',', '.') ?></span>
                    </div>
                    <h3><?= e($i['nome']) ?></h3>
                    <p class="menu-description"><?= e($i['descricao'] ?: 'Sem descrição.') ?></p>
                    <div class="menu-card-actions">
                        <a class="text-link" href="cardapio.php?acao=editar&amp;id=<?= e($i['id']) ?>">Editar</a>
                        <form class="inline-form" method="post" action="cardapio.php">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?= e($i['id']) ?>">
                            <button type="submit">Excluir</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/../layout/rodape.php'; ?>
