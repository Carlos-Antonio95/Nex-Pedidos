<?php
$paginaAtual = 'pedidos';
$titulo = 'Detalhe do pedido';
include __DIR__ . '/../layout/cabecalho.php';
?>
<section class="view">
    <div class="page-heading">
        <div>
            <p class="eyebrow">ACOMPANHAMENTO</p>
            <h1><?= $pedido !== null ? 'Pedido #' . e($pedido['id']) : 'Pedido não encontrado' ?></h1>
            <p class="page-subtitle">Consulte os itens, o total e o andamento do pedido.</p>
        </div>
        <a class="button button-outline" href="pedidos.php">Voltar aos pedidos</a>
    </div>

    <?php if ($mensagem !== ''): ?>
        <div class="notice notice-success" role="status"><?= e($mensagem) ?></div>
    <?php endif; ?>
    <?php if (!empty($erros)): ?>
        <div class="notice notice-error" role="alert">
            <ul><?php foreach ($erros as $erro): ?><li><?= e($erro) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <?php if ($pedido !== null): ?>
        <?php
        $statusClasse = [
            'Recebido' => 'badge-recebido',
            'Em preparo' => 'badge-preparo',
            'Pronto' => 'badge-pronto',
            'Entregue' => 'badge-entregue',
            'Cancelado' => 'badge-cancelado',
        ][$pedido['status']] ?? '';
        $destino = $pedido['mesa'] !== null ? 'Mesa ' . $pedido['mesa'] : $pedido['cliente'];
        $criadoEm = strtotime($pedido['criado_em']);
        ?>
        <section class="detail-panel">
            <div class="detail-meta">
                <span><?= e($destino) ?><?= $criadoEm ? ' · ' . e(date('d/m/Y H:i', $criadoEm)) : '' ?></span>
                <span class="badge <?= e($statusClasse) ?>"><?= e($pedido['status']) ?></span>
            </div>

            <div class="panel-heading"><h2>Itens do pedido</h2></div>
            <?php if (empty($itensPedido)): ?>
                <p class="form-hint">Nenhum item adicionado a este pedido.</p>
            <?php else: ?>
                <ul class="order-item-list">
                    <?php foreach ($itensPedido as $ip): ?>
                        <li class="order-item-row">
                            <span class="order-item-name">
                                <?= e($ip['nome']) ?>
                                <small><?= e($ip['quantidade']) ?> × R$ <?= number_format((float) $ip['preco_unitario'], 2, ',', '.') ?></small>
                            </span>
                            <span class="order-item-total">R$ <?= number_format((float) $ip['subtotal'], 2, ',', '.') ?></span>
                            <?php if ($proximoStatus !== null): ?>
                                <form class="inline-form" method="post" action="pedido.php">
                                    <input type="hidden" name="acao" value="remover">
                                    <input type="hidden" name="pedido_id" value="<?= e($pedido['id']) ?>">
                                    <input type="hidden" name="item_id" value="<?= e($ip['item_id']) ?>">
                                    <input type="hidden" name="quantidade" value="1">
                                    <button class="button button-outline" type="submit" aria-label="Remover uma unidade de <?= e($ip['nome']) ?>">Remover 1</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="detail-total"><span>Total do pedido</span><strong>R$ <?= number_format((float) $total, 2, ',', '.') ?></strong></div>

            <?php if ($proximoStatus !== null): ?>
                <form class="detail-form" method="post" action="pedido.php">
                    <input type="hidden" name="acao" value="adicionar">
                    <input type="hidden" name="pedido_id" value="<?= e($pedido['id']) ?>">
                    <label class="field-label" for="item_id">Adicionar item do cardápio</label>
                    <div class="form-row">
                        <select class="text-input" id="item_id" name="item_id" required>
                            <option value="">Selecione um item</option>
                            <?php foreach ($itensCardapio as $i): ?>
                                <option value="<?= e($i['id']) ?>"><?= e($i['nome']) ?> — R$ <?= number_format((float) $i['preco'], 2, ',', '.') ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input class="text-input" name="quantidade" type="number" min="1" step="1" value="1" required aria-label="Quantidade">
                    </div>
                    <div class="form-actions"><button class="button button-primary" type="submit">Adicionar ao pedido</button></div>
                </form>
            <?php endif; ?>

            <div class="detail-controls">
                <?php if ($proximoStatus !== null): ?>
                    <form method="post" action="pedido.php">
                        <input type="hidden" name="acao" value="status">
                        <input type="hidden" name="pedido_id" value="<?= e($pedido['id']) ?>">
                        <input type="hidden" name="status" value="<?= e($proximoStatus) ?>">
                        <button class="button button-primary" type="submit">Marcar como <?= e($proximoStatus) ?></button>
                    </form>
                    <form method="post" action="pedido.php">
                        <input type="hidden" name="acao" value="cancelar">
                        <input type="hidden" name="pedido_id" value="<?= e($pedido['id']) ?>">
                        <button class="button button-outline" type="submit">Cancelar pedido</button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/../layout/rodape.php'; ?>
