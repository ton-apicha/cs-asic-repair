<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-hdd-rack me-2"></i><?= lang('App.assetDetails') ?>
    </h1>
    <div class="d-flex gap-2">
        <a href="<?= base_url('jobs/create-from-asset/' . $asset['id']) ?>" class="btn btn-primary">
            <i class="bi bi-clipboard-plus me-1"></i><?= lang('App.createJobFromAsset') ?>
        </a>
        <a href="<?= base_url('machines/edit/' . $asset['id']) ?>" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i><?= lang('App.edit') ?>
        </a>
        <a href="<?= base_url('machines') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Asset Info -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i><?= lang('App.assetDetails') ?>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-cpu-fill text-info" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mt-3 mb-1"><?= esc($asset['brand_model']) ?></h5>
                    <?php
                    $statusClass = match($asset['status']) {
                        'stored' => 'badge-asset-stored',
                        'repairing' => 'badge-asset-repairing',
                        'repaired' => 'badge-asset-repaired',
                        'returned' => 'badge-asset-returned',
                        default => 'bg-secondary'
                    };
                    $statusText = match($asset['status']) {
                        'stored' => lang('App.assetStatusStored'),
                        'repairing' => lang('App.assetStatusRepairing'),
                        'repaired' => lang('App.assetStatusRepaired'),
                        'returned' => lang('App.assetStatusReturned'),
                        default => $asset['status']
                    };
                    ?>
                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.assetSerialNumber') ?></small>
                    <strong><code class="fs-6"><?= esc($asset['serial_number']) ?></code></strong>
                </div>
                
                <?php if ($asset['mac_address']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.assetMacAddress') ?></small>
                    <strong><code><?= esc($asset['mac_address']) ?></code></strong>
                </div>
                <?php endif; ?>
                
                <?php if ($asset['hash_rate']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.assetHashRate') ?></small>
                    <strong><?= number_format($asset['hash_rate'], 2) ?> TH/s</strong>
                </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.customer') ?></small>
                    <a href="<?= base_url('customers/view/' . $customer['id']) ?>" class="text-decoration-none">
                        <i class="bi bi-person me-1"></i><?= esc($customer['name']) ?>
                    </a>
                </div>
                
                <?php if ($asset['external_condition']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.assetCondition') ?></small>
                    <p class="mb-0"><?= nl2br(esc($asset['external_condition'])) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($asset['notes']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.notes') ?></small>
                    <p class="mb-0"><?= nl2br(esc($asset['notes'])) ?></p>
                </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="small text-muted">
                    <div><?= lang('App.createdAt') ?>: <?= date('d/m/Y H:i', strtotime($asset['created_at'])) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Repair History -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i><?= lang('App.assetHistory') ?></span>
                <span class="badge bg-info"><?= count($jobs ?? []) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($jobs)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox d-block fs-1 mb-2 opacity-50"></i>
                        <p class="mb-3"><?= lang('App.noJobsFound') ?></p>
                        <a href="<?= base_url('jobs/create-from-asset/' . $asset['id']) ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i><?= lang('App.newJob') ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><?= lang('App.jobId') ?></th>
                                    <th><?= lang('App.jobSymptom') ?></th>
                                    <th><?= lang('App.status') ?></th>
                                    <th><?= lang('App.grandTotal') ?></th>
                                    <th><?= lang('App.date') ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jobs as $job): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="fw-semibold text-decoration-none">
                                            <?= esc($job['job_id']) ?>
                                        </a>
                                        <?php if ($job['is_warranty_claim']): ?>
                                            <span class="badge bg-danger ms-1">W</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?= esc($job['symptom']) ?>">
                                            <?= esc($job['symptom']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($job['status']) {
                                            'new_checkin' => 'badge-status-new',
                                            'pending_repair' => 'badge-status-pending',
                                            'in_progress' => 'badge-status-progress',
                                            'repair_done' => 'badge-status-done',
                                            'ready_handover' => 'badge-status-ready',
                                            'delivered' => 'badge-status-delivered',
                                            'cancelled' => 'badge-status-cancelled',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $job['status'])) ?></span>
                                    </td>
                                    <td>฿<?= number_format($job['grand_total'], 0) ?></td>
                                    <td class="text-muted small"><?= date('d/m/Y', strtotime($job['created_at'])) ?></td>
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
    </div>
</div>
<?= $this->endSection() ?>

