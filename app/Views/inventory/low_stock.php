<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-exclamation-triangle text-warning me-2"></i><?= lang('App.lowStockAlert') ?>
    </h1>
    <a href="<?= base_url('inventory') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($parts)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-check-circle text-success d-block fs-1 mb-2"></i>
                <h5>All stock levels are healthy!</h5>
                <p class="mb-0">No parts are below their reorder points.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.partCode') ?></th>
                            <th><?= lang('App.partName') ?></th>
                            <th><?= lang('App.partLocation') ?></th>
                            <th class="text-center"><?= lang('App.quantity') ?></th>
                            <th class="text-center"><?= lang('App.reorderPoint') ?></th>
                            <th class="text-center">Shortage</th>
                            <th><?= lang('App.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parts as $part): ?>
                        <?php $shortage = $part['reorder_point'] - $part['quantity']; ?>
                        <tr>
                            <td><code><?= esc($part['part_code']) ?></code></td>
                            <td>
                                <a href="<?= base_url('inventory/view/' . $part['id']) ?>" class="fw-semibold text-decoration-none">
                                    <?= esc($part['name']) ?>
                                </a>
                            </td>
                            <td><?= esc($part['location'] ?? '-') ?></td>
                            <td class="text-center">
                                <span class="badge <?= $part['quantity'] == 0 ? 'bg-danger' : 'bg-warning text-dark' ?> fs-6">
                                    <?= $part['quantity'] ?>
                                </span>
                            </td>
                            <td class="text-center"><?= $part['reorder_point'] ?></td>
                            <td class="text-center text-danger fw-bold">-<?= $shortage ?></td>
                            <td>
                                <a href="<?= base_url('inventory/edit/' . $part['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Restock
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

