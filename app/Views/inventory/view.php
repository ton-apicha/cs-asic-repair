<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-box-seam me-2"></i><?= lang('App.partDetails') ?>
    </h1>
    <div class="d-flex gap-2">
        <a href="<?= base_url('inventory/edit/' . $part['id']) ?>" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i><?= lang('App.edit') ?>
        </a>
        <a href="<?= base_url('inventory') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Part Info -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i><?= lang('App.partDetails') ?>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-box-seam text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mt-3 mb-1"><?= esc($part['name']) ?></h5>
                    <code class="text-muted"><?= esc($part['part_code']) ?></code>
                </div>
                
                <hr>
                
                <div class="row text-center mb-4">
                    <div class="col-4">
                        <div class="fw-bold fs-4 <?= $part['quantity'] <= $part['reorder_point'] ? 'text-danger' : 'text-success' ?>">
                            <?= $part['quantity'] ?>
                        </div>
                        <small class="text-muted"><?= lang('App.inStock') ?></small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-primary">฿<?= number_format($part['cost_price'], 0) ?></div>
                        <small class="text-muted"><?= lang('App.partCostPrice') ?></small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-success">฿<?= number_format($part['sell_price'], 0) ?></div>
                        <small class="text-muted"><?= lang('App.partSellPrice') ?></small>
                    </div>
                </div>
                
                <hr>
                
                <?php if ($part['description']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.description') ?></small>
                    <p class="mb-0"><?= nl2br(esc($part['description'])) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($part['location']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.partLocation') ?></small>
                    <strong><i class="bi bi-geo-alt me-1"></i><?= esc($part['location']) ?></strong>
                </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.reorderPoint') ?></small>
                    <strong><?= $part['reorder_point'] ?> units</strong>
                </div>
                
                <?php if ($part['serial_number']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.partSerialNumber') ?></small>
                    <code><?= esc($part['serial_number']) ?></code>
                </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="small text-muted">
                    <div><?= lang('App.createdAt') ?>: <?= date('d/m/Y H:i', strtotime($part['created_at'])) ?></div>
                    <div><?= lang('App.updatedAt') ?>: <?= date('d/m/Y H:i', strtotime($part['updated_at'])) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaction History -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i><?= lang('App.stockTransaction') ?></span>
                <span class="badge bg-info"><?= count($transactions ?? []) ?></span>
            </div>
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
                                    <th><?= lang('App.type') ?></th>
                                    <th><?= lang('App.quantity') ?></th>
                                    <th>Reference</th>
                                    <th><?= lang('App.notes') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $tx): ?>
                                <tr>
                                    <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?></td>
                                    <td>
                                        <?php
                                        $typeClass = match($tx['type']) {
                                            'in' => 'bg-success',
                                            'out' => 'bg-danger',
                                            'adjustment' => 'bg-warning',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $typeClass ?>"><?= ucfirst($tx['type']) ?></span>
                                    </td>
                                    <td>
                                        <span class="<?= $tx['quantity'] > 0 ? 'text-success' : 'text-danger' ?>">
                                            <?= $tx['quantity'] > 0 ? '+' : '' ?><?= $tx['quantity'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($tx['job_id']): ?>
                                            <a href="<?= base_url('jobs/view/' . $tx['job_id']) ?>">Job #<?= $tx['job_id'] ?></a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?= esc($tx['notes'] ?? '-') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

