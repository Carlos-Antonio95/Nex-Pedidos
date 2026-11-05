<?php
$paginaAtual = 'cardapio';
$editando = !empty($item['id']);
$titulo = $editando ? 'Editar item' : 'Novo item';
include __DIR__ . '/../layout/cabecalho.php';
?>
<section class="view">
    <div class="page-heading">
        <div>
            <p class="eyebrow">SEUS PRODUTOS</p>
            <h1><?= $editando ? 'Editar item' : 'Adicionar item' ?></h1>
            <p class="page-subtitle">Preencha as informações do produto do cardápio.</p>
        </div>
    </div>

    <?php if (!empty($erros)): ?>
        <div class="notice notice-error" role="alert">
            <ul><?php foreach ($erros as $erro): ?><li><?= e($erro) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <section class="form-panel">
        <form method="post" action="cardapio.php">
            <input type="hidden" name="acao" value="<?= $editando ? 'editar' : 'cadastrar' ?>">
            <?php if ($editando): ?><input type="hidden" name="id" value="<?= e($item['id']) ?>"><?php endif; ?>

            <label class="field-label" for="nome">Nome do item</label>
            <input class="text-input" id="nome" name="nome" type="text" value="<?= e($item['nome']) ?>" autocomplete="off">

            <label class="field-label" for="descricao">Descrição <span class="form-hint">(opcional)</span></label>
            <textarea class="text-input" id="descricao" name="descricao" rows="3"><?= e($item['descricao']) ?></textarea>

            <div class="form-row">
                <div>
                    <label class="field-label" for="preco">Preço (R$)</label>
                    <input class="text-input" id="preco" name="preco" type="text" inputmode="decimal" value="<?= e($item['preco']) ?>" placeholder="Ex.: 8,50">
                </div>
                <div>
                    <label class="field-label" for="categoria">Categoria</label>
                    <input class="text-input" id="categoria" name="categoria" type="text" value="<?= e($item['categoria']) ?>" placeholder="Ex.: Bebidas">
                </div>
            </div>

            <div class="form-actions">
                <a class="button button-outline" href="cardapio.php">Voltar</a>
                <button class="button button-primary" type="submit"><?= $editando ? 'Salvar alterações' : 'Cadastrar item' ?></button>
            </div>
        </form>
    </section>
</section>
<?php include __DIR__ . '/../layout/rodape.php'; ?>
