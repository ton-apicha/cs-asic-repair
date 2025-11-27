<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-hourglass-split me-2"></i><?= lang('App.wipReport') ?>
    </h1>
    <div class="d-flex gap-2">
        <a href="<?= base_url('jobs/kanban') ?>" class="btn btn-outline-primary">
            <i class="bi bi-kanban me-1"></i>Kanban View
        </a>
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i><?= lang('App.print') ?>
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <?php
    $statuses = [
        'new_checkin' => ['label' => lang('App.statusNewCheckin'), 'color' => 'secondary', 'icon' => 'inbox'],
        'pending_repair' => ['label' => lang('App.statusPendingRepair'), 'color' => 'warning', 'icon' => 'hourglass-split'],
        'in_progress' => ['label' => lang('App.statusInProgress'), 'color' => 'primary', 'icon' => 'tools'],
        'repair_done' => ['label' => lang('App.statusRepairDone'), 'color' => 'success', 'icon' => 'check-circle'],
        'ready_handover' => ['label' => lang('App.statusReadyHandover'), 'color' => 'info', 'icon' => 'box-seam'],
    ];
    ?>
    <?php foreach ($statuses as $key => $status): ?>
    <div class="col">
        <div class="card border-<?= $status['color'] ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-<?= $status['color'] ?>"><?= $status['label'] ?></h6>
                        <h3 class="mb-0"><?= $summary[$key] ?? 0 ?></h3>
                    </div>
                    <i class="bi bi-<?= $status['icon'] ?> fs-1 text-<?= $status['color'] ?> opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- WIP Table -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-table me-2"></i>Work In Progress Jobs
    </div>
    <div class="card-body p-0">
        <?php if (empty($jobs)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-check-circle text-success d-block fs-1 mb-2"></i>
                <p class="mb-0">All jobs are completed!</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.jobId') ?></th>
                            <th>Check-in Date</th>
                            <th>Days Open</th>
                            <th><?= lang('App.customer') ?></th>
                            <th><?= lang('App.asset') ?></th>
                            <th><?= lang('App.status') ?></th>
                            <th><?= lang('App.jobTechnician') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                        <?php 
                        $daysOpen = (strtotime('now') - strtotime($job['created_at'])) / 86400;
                        $daysClass = $daysOpen > 7 ? 'text-danger' : ($daysOpen > 3 ? 'text-warning' : 'text-success');
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="fw-bold">
                                    <?= esc($job['job_id']) ?>
                                </a>
                                <?php if ($job['is_warranty_claim']): ?>
                                    <span class="badge bg-danger ms-1">W</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($job['created_at'])) ?></td>
                            <td class="<?= $daysClass ?> fw-bold">
                                <?= number_format($daysOpen, 0) ?> days
                            </td>
                            <td><?= esc($job['customer_name']) ?></td>
                            <td>
                                <div><?= esc($job['brand_model']) ?></div>
                                <small class="text-muted"><?= esc($job['serial_number']) ?></small>
                            </td>
                            <td>
                                <?php
                                $statusClass = match($job['status']) {
                                    'new_checkin' => 'badge-status-new',
                                    'pending_repair' => 'badge-status-pending',
                                    'in_progress' => 'badge-status-progress',
                                    'repair_done' => 'badge-status-done',
                                    'ready_handover' => 'badge-status-ready',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $job['status'])) ?></span>
                            </td>
                            <td><?= esc($job['technician_name'] ?? '-') ?></td>
                            <td>
                                <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
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

