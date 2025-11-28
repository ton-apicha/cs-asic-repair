<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-title i {
        color: var(--info-color);
    }
    
    .filter-card {
        background: var(--card-bg);
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
    }
    
    .data-card {
        background: var(--card-bg);
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    
    .jobs-table {
        margin-bottom: 0;
    }
    
    .jobs-table th {
        background: var(--table-stripe);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        padding: 1rem;
        white-space: nowrap;
        border-bottom: 2px solid var(--border-color);
    }
    
    .jobs-table td {
        padding: 1rem;
        vertical-align: middle;
        color: var(--text-primary);
        border-color: var(--border-color);
    }
    
    .jobs-table tbody tr {
        transition: background 0.15s ease;
    }
    
    .jobs-table tbody tr:hover {
        background: var(--table-stripe);
    }
    
    .job-link {
        color: var(--info-color);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    
    .job-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }
    
    .warranty-badge {
        font-size: 0.6875rem;
        font-weight: 700;
        padding: 0.125rem 0.375rem;
        vertical-align: middle;
    }
    
    .asset-cell {
        max-width: 200px;
    }
    
    .asset-model {
        font-weight: 500;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .asset-serial {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }
    
    .action-btns .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.8125rem;
    }
    
    .empty-state {
        padding: 4rem 1rem;
        text-align: center;
        color: var(--text-muted);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .status-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .status-tab {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid var(--border-color);
        background: var(--content-bg);
        color: var(--text-secondary);
        transition: all 0.15s ease;
    }
    
    .status-tab:hover {
        background: var(--card-bg);
        color: var(--text-primary);
    }
    
    .status-tab.active {
        background: var(--info-color);
        border-color: var(--info-color);
        color: #fff;
    }
    
    .search-input {
        max-width: 280px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <h1 class="page-title mb-0">
        <i class="bi bi-clipboard-check"></i>
        <?= lang('App.jobs') ?>
    </h1>
    <div class="d-flex gap-2">
        <a href="<?= base_url('jobs/kanban') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-kanban me-1"></i><?= lang('App.kanbanBoard') ?>
        </a>
        <a href="<?= base_url('jobs/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i><?= lang('App.newJob') ?>
        </a>
    </div>
</div>

<!-- Filters -->
<div class="filter-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <!-- Status Tabs -->
        <div class="status-tabs">
            <a href="<?= base_url('jobs') ?>" class="status-tab <?= empty($currentStatus) ? 'active' : '' ?>">
                <?= lang('App.all') ?>
            </a>
            <a href="<?= base_url('jobs?status=new_checkin') ?>" class="status-tab <?= $currentStatus === 'new_checkin' ? 'active' : '' ?>">
                <?= lang('App.statusNewCheckin') ?>
            </a>
            <a href="<?= base_url('jobs?status=pending_repair') ?>" class="status-tab <?= $currentStatus === 'pending_repair' ? 'active' : '' ?>">
                <?= lang('App.statusPendingRepair') ?>
            </a>
            <a href="<?= base_url('jobs?status=in_progress') ?>" class="status-tab <?= $currentStatus === 'in_progress' ? 'active' : '' ?>">
                <?= lang('App.statusInProgress') ?>
            </a>
            <a href="<?= base_url('jobs?status=repair_done') ?>" class="status-tab <?= $currentStatus === 'repair_done' ? 'active' : '' ?>">
                <?= lang('App.statusRepairDone') ?>
            </a>
            <a href="<?= base_url('jobs?status=ready_handover') ?>" class="status-tab <?= $currentStatus === 'ready_handover' ? 'active' : '' ?>">
                <?= lang('App.statusReadyHandover') ?>
            </a>
            <a href="<?= base_url('jobs?status=delivered') ?>" class="status-tab <?= $currentStatus === 'delivered' ? 'active' : '' ?>">
                <?= lang('App.statusDelivered') ?>
            </a>
        </div>
        
        <!-- Search -->
        <div class="search-input">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" id="searchInput" 
                       placeholder="<?= lang('App.searchJob') ?>" onkeyup="searchTable()">
            </div>
        </div>
    </div>
</div>

<!-- Jobs Table -->
<div class="data-card">
    <?php if (empty($jobs)): ?>
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h5 class="fw-semibold mb-2"><?= lang('App.noJobsFound') ?></h5>
            <p class="mb-3">No job cards match your current filter</p>
            <a href="<?= base_url('jobs/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Create New Job
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table jobs-table" id="jobsTable">
                <thead>
                    <tr>
                        <th><?= lang('App.jobId') ?></th>
                        <th><?= lang('App.customer') ?></th>
                        <th><?= lang('App.asset') ?></th>
                        <th><?= lang('App.status') ?></th>
                        <th class="text-end"><?= lang('App.grandTotal') ?></th>
                        <th><?= lang('App.date') ?></th>
                        <th class="text-center"><?= lang('App.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="job-link">
                                    <?= esc($job['job_id']) ?>
                                </a>
                                <?php if ($job['is_warranty_claim']): ?>
                                    <span class="badge bg-danger warranty-badge ms-1" title="Warranty Claim">W</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-medium"><?= esc($job['customer_name']) ?></span>
                            </td>
                            <td class="asset-cell">
                                <div class="asset-model"><?= esc($job['brand_model']) ?></div>
                                <div class="asset-serial"><?= esc($job['serial_number']) ?></div>
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
                                $statusText = match($job['status']) {
                                    'new_checkin' => lang('App.statusNewCheckin'),
                                    'pending_repair' => lang('App.statusPendingRepair'),
                                    'in_progress' => lang('App.statusInProgress'),
                                    'repair_done' => lang('App.statusRepairDone'),
                                    'ready_handover' => lang('App.statusReadyHandover'),
                                    'delivered' => lang('App.statusDelivered'),
                                    'cancelled' => lang('App.statusCancelled'),
                                    default => $job['status']
                                };
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                            </td>
                            <td class="text-end">
                                <span class="fw-semibold">฿<?= number_format($job['grand_total'], 0) ?></span>
                            </td>
                            <td>
                                <span class="text-muted"><?= date('d M Y', strtotime($job['created_at'])) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="action-btns btn-group btn-group-sm">
                                    <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="btn btn-outline-primary" 
                                       title="<?= lang('App.view') ?>" data-bs-toggle="tooltip">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (!$job['is_locked']): ?>
                                        <a href="<?= base_url('jobs/edit/' . $job['id']) ?>" class="btn btn-outline-secondary" 
                                           title="<?= lang('App.edit') ?>" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('jobs/print/' . $job['id']) ?>" class="btn btn-outline-info" 
                                       target="_blank" title="<?= lang('App.print') ?>" data-bs-toggle="tooltip">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function searchTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const table = document.getElementById('jobsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?= $this->endSection() ?>
