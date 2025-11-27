<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-clock-history me-2"></i><?= lang('App.stockTransaction') ?>
        <?php if (isset($part)): ?>
            - <?= esc($part['name']) ?>
        <?php endif; ?>
    </h1>
    <a href="<?= isset($part) ? base_url('inventory/view/' . $part['id']) : base_url('inventory') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($transactions)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox d-block fs-1 mb-2 opacity-50"></i>
                <p class="mb-0">No transactions recorded</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.date') ?></th>
                            <?php if (!isset($part)): ?>
                            <th><?= lang('App.part') ?></th>
                            <?php endif; ?>
                            <th><?= lang('App.type') ?></th>
                            <th class="text-center"><?= lang('App.quantity') ?></th>
                            <th>Reference</th>
                            <th><?= lang('App.notes') ?></th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td class="text-muted"><?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?></td>
                            <?php if (!isset($part)): ?>
                            <td>
                                <a href="<?= base_url('inventory/view/' . $tx['part_id']) ?>">
                                    <?= esc($tx['part_name'] ?? 'Part #'.$tx['part_id']) ?>
                                </a>
                            </td>
                            <?php endif; ?>
                            <td>
                                <?php
                                $typeClass = match($tx['type']) {
                                    'in' => 'bg-success',
                                    'out' => 'bg-danger',
                                    'adjustment' => 'bg-warning',
                                    default => 'bg-secondary'
                                };
                                $typeIcon = match($tx['type']) {
                                    'in' => 'bi-arrow-down-circle',
                                    'out' => 'bi-arrow-up-circle',
                                    'adjustment' => 'bi-arrow-repeat',
                                    default => 'bi-circle'
                                };
                                ?>
                                <span class="badge <?= $typeClass ?>">
                                    <i class="bi <?= $typeIcon ?> me-1"></i><?= ucfirst($tx['type']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold <?= $tx['quantity'] > 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= $tx['quantity'] > 0 ? '+' : '' ?><?= $tx['quantity'] ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($tx['job_id']): ?>
                                    <a href="<?= base_url('jobs/view/' . $tx['job_id']) ?>" class="text-decoration-none">
                                        <i class="bi bi-clipboard-check me-1"></i>Job #<?= $tx['job_id'] ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($tx['notes'] ?? '-') ?></td>
                            <td class="text-muted small"><?= esc($tx['user_name'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

