<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-shield-check me-2"></i><?= lang('App.warrantyReport') ?>
    </h1>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i><?= lang('App.print') ?>
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label"><?= lang('App.date') ?> (From)</label>
                <input type="date" class="form-control" name="from" value="<?= $from ?? date('Y-m-01') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label"><?= lang('App.date') ?> (To)</label>
                <input type="date" class="form-control" name="to" value="<?= $to ?? date('Y-m-d') ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i><?= lang('App.filter') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Claims</h6>
                        <h3 class="mb-0"><?= $summary['total_claims'] ?? 0 ?></h3>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Cost</h6>
                        <h3 class="mb-0">฿<?= number_format($summary['total_cost'] ?? 0, 0) ?></h3>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Claim Rate</h6>
                        <h3 class="mb-0"><?= number_format($summary['claim_rate'] ?? 0, 1) ?>%</h3>
                    </div>
                    <i class="bi bi-percent fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Claims Table -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-table me-2"></i>Warranty Claims
    </div>
    <div class="card-body p-0">
        <?php if (empty($claims)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-check-circle text-success d-block fs-1 mb-2"></i>
                <p class="mb-0">No warranty claims in this period</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.date') ?></th>
                            <th>Claim Job</th>
                            <th>Original Job</th>
                            <th><?= lang('App.customer') ?></th>
                            <th><?= lang('App.asset') ?></th>
                            <th><?= lang('App.status') ?></th>
                            <th class="text-end">Claim Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($claims as $claim): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($claim['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('jobs/view/' . $claim['id']) ?>" class="fw-bold">
                                    <?= esc($claim['job_id']) ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($claim['original_job_id']): ?>
                                    <a href="<?= base_url('jobs/view/' . $claim['original_job_id']) ?>">
                                        View Original
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($claim['customer_name']) ?></td>
                            <td>
                                <div><?= esc($claim['brand_model']) ?></div>
                                <small class="text-muted"><?= esc($claim['serial_number']) ?></small>
                            </td>
                            <td>
                                <?php
                                $statusClass = match($claim['status']) {
                                    'new_checkin' => 'badge-status-new',
                                    'pending_repair' => 'badge-status-pending',
                                    'in_progress' => 'badge-status-progress',
                                    'repair_done' => 'badge-status-done',
                                    'ready_handover' => 'badge-status-ready',
                                    'delivered' => 'badge-status-delivered',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $claim['status'])) ?></span>
                            </td>
                            <td class="text-end fw-bold text-danger">฿<?= number_format($claim['parts_cost'], 0) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

