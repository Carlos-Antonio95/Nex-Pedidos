<?php
$paginaAtual = 'pedidos';
$titulo = 'Pedidos';
include __DIR__ . '/../layout/cabecalho.php';
?>
<section class="view">
    <div class="page-heading">
        <div>
            <p class="eyebrow">VISÃO GERAL</p>
            <h1>Pedidos</h1>
            <p class="page-subtitle">Acompanhe e gerencie os pedidos do seu restaurante.</p>
        </div>
    </div>

    <?php if (!empty($erros)): ?>
        <div class="notice notice-error" role="alert">
            <ul><?php foreach ($erros as $erro): ?><li><?= e($erro) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <section class="orders-panel">
        <div class="panel-heading">
            <div><h2>Todos os pedidos</h2><p>Abra um pedido para acompanhar os itens e atualizar seu status.</p></div>
            <form method="get" action="pedidos.php" class="filter-wrap">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Todos</option>
                    <?php foreach ($listaStatus as $status): ?>
                        <option value="<?= e($status) ?>"<?= $status === $statusFiltro ? ' selected' : '' ?>><?= e($status) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="button button-outline" type="submit">Filtrar</button>
            </form>
        </div>
        <?php if (empty($pedidos)): ?>
            <div class="empty-state">
                <div class="empty-icon" aria-hidden="true">▦</div>
                <h3><?= e($mensagem !== '' ? $mensagem : 'Nenhum pedido encontrado.') ?></h3>
                <p>Abra um pedido para uma mesa ou cliente usando o formulário abaixo.</p>
            </div>
        <?php else: ?>
            <div class="orders-table-wrap">
                <table class="orders-table">
                    <thead><tr><th>PEDIDO</th><th>CLIENTE / MESA</th><th>HORÁRIO</th><th>VALOR TOTAL</th><th>STATUS</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($pedidos as $p): ?>
                        <?php
                        $statusClasse = [
                            'Recebido' => 'badge-recebido',
                            'Em preparo' => 'badge-preparo',
                            'Pronto' => 'badge-pronto',
                            'Entregue' => 'badge-entregue',
                            'Cancelado' => 'badge-cancelado',
                        ][$p['status']] ?? '';
                        $destino = $p['mesa'] !== null ? 'Mesa ' . str_pad($p['mesa'], 2, '0', STR_PAD_LEFT) : $p['cliente'];
                        $horario = strtotime($p['criado_em']);
                        ?>
                        <tr>
                            <td><span class="order-number">#<?= e($p['id']) ?></span></td>
                            <td class="customer-cell"><?= e($destino) ?></td>
                            <td><?= $horario ? e(date('d/m/Y H:i', $horario)) : e($p['criado_em']) ?></td>
                            <td class="total-cell">R$ <?= number_format((float) $p['total'], 2, ',', '.') ?></td>
                            <td><span class="badge <?= e($statusClasse) ?>"><?= e($p['status']) ?></span></td>
                            <td><a class="text-link" href="pedido.php?id=<?= e($p['id']) ?>">Abrir pedido</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="form-panel">
        <div class="panel-heading"><div><h2>Abrir pedido</h2><p>Informe uma mesa, um cliente ou os dois.</p></div></div>
        <form method="post" action="pedidos.php">
            <input type="hidden" name="acao" value="abrir">
            <div class="form-row">
                <div>
                    <label class="field-label" for="mesa">Número da mesa <span class="form-hint">(opcional)</span></label>
                    <input class="text-input" id="mesa" name="mesa" type="text" inputmode="numeric" value="<?= e($mesa) ?>" placeholder="Ex.: 4">
                </div>
                <div>
                    <label class="field-label" for="cliente">Nome do cliente <span class="form-hint">(opcional)</span></label>
                    <input class="text-input" id="cliente" name="cliente" type="text" value="<?= e($cliente) ?>" placeholder="Ex.: Ana Souza">
                </div>
            </div>
            <p class="form-hint">Preencha ao menos um dos campos.</p>
            <div class="form-actions"><button class="button button-primary" type="submit">Abrir pedido</button></div>
        </form>
    </section>
</section>
<?php include __DIR__ . '/../layout/rodape.php'; ?>
