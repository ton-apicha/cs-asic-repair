<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-person me-2"></i><?= lang('App.customerDetails') ?>
    </h1>
    <div class="d-flex gap-2">
        <a href="<?= base_url('customers/edit/' . $customer['id']) ?>" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i><?= lang('App.edit') ?>
        </a>
        <a href="<?= base_url('customers') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Customer Info -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i><?= lang('App.customerDetails') ?>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mt-3 mb-1"><?= esc($customer['name']) ?></h5>
                    <span class="badge bg-<?= $customer['is_active'] ? 'success' : 'secondary' ?>">
                        <?= $customer['is_active'] ? lang('App.active') : lang('App.inactive') ?>
                    </span>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.phone') ?></small>
                    <strong><i class="bi bi-telephone me-2"></i><?= esc($customer['phone']) ?></strong>
                </div>
                
                <?php if ($customer['email']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.email') ?></small>
                    <strong><i class="bi bi-envelope me-2"></i><?= esc($customer['email']) ?></strong>
                </div>
                <?php endif; ?>
                
                <?php if ($customer['address']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.address') ?></small>
                    <p class="mb-0"><i class="bi bi-geo-alt me-2"></i><?= nl2br(esc($customer['address'])) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($customer['tax_id']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.customerTaxId') ?></small>
                    <strong><i class="bi bi-building me-2"></i><?= esc($customer['tax_id']) ?></strong>
                </div>
                <?php endif; ?>
                
                <?php if ($customer['notes']): ?>
                <div class="mb-3">
                    <small class="text-muted d-block"><?= lang('App.notes') ?></small>
                    <p class="mb-0"><?= nl2br(esc($customer['notes'])) ?></p>
                </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="small text-muted">
                    <div><?= lang('App.createdAt') ?>: <?= date('d/m/Y H:i', strtotime($customer['created_at'])) ?></div>
                    <div><?= lang('App.updatedAt') ?>: <?= date('d/m/Y H:i', strtotime($customer['updated_at'])) ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Assets & Jobs -->
    <div class="col-lg-8">
        <!-- Assets -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-hdd-rack me-2"></i><?= lang('App.assets') ?></span>
                <span class="badge bg-primary"><?= count($assets ?? []) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($assets)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox d-block fs-1 mb-2 opacity-50"></i>
                        <?= lang('App.noAssetsFound') ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><?= lang('App.assetBrandModel') ?></th>
                                    <th><?= lang('App.assetSerialNumber') ?></th>
                                    <th><?= lang('App.status') ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assets as $asset): ?>
                                <tr>
                                    <td><?= esc($asset['brand_model']) ?></td>
                                    <td><code><?= esc($asset['serial_number']) ?></code></td>
                                    <td>
                                        <?php
                                        $statusClass = match($asset['status']) {
                                            'stored' => 'badge-asset-stored',
                                            'repairing' => 'badge-asset-repairing',
                                            'repaired' => 'badge-asset-repaired',
                                            'returned' => 'badge-asset-returned',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= ucfirst($asset['status']) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('machines/view/' . $asset['id']) ?>" class="btn btn-sm btn-outline-primary">
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
        
        <!-- Recent Jobs -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard-check me-2"></i><?= lang('App.recentJobs') ?></span>
                <span class="badge bg-info"><?= count($jobs ?? []) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($jobs)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox d-block fs-1 mb-2 opacity-50"></i>
                        <?= lang('App.noJobsFound') ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><?= lang('App.jobId') ?></th>
                                    <th><?= lang('App.asset') ?></th>
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
                                    </td>
                                    <td><?= esc($job['brand_model'] ?? '-') ?></td>
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

