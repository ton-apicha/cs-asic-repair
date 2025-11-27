<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-title i {
        color: #3b82f6;
    }
    
    /* Kanban Board */
    .kanban-board {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        min-height: calc(100vh - 180px);
    }
    
    .kanban-column {
        flex: 0 0 320px;
        background: #f8fafc;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 200px);
        animation: fadeIn 0.4s ease forwards;
        opacity: 0;
    }
    
    .kanban-column:nth-child(1) { animation-delay: 0.1s; }
    .kanban-column:nth-child(2) { animation-delay: 0.15s; }
    .kanban-column:nth-child(3) { animation-delay: 0.2s; }
    .kanban-column:nth-child(4) { animation-delay: 0.25s; }
    .kanban-column:nth-child(5) { animation-delay: 0.3s; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .kanban-column-header {
        padding: 1rem 1.25rem;
        font-weight: 600;
        font-size: 0.9375rem;
        border-bottom: 3px solid;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border-radius: 1rem 1rem 0 0;
    }
    
    .kanban-count {
        font-size: 0.75rem;
        padding: 0.25rem 0.625rem;
    }
    
    .kanban-column-body {
        flex: 1;
        overflow-y: auto;
        padding: 0.75rem;
        min-height: 200px;
    }
    
    .kanban-card {
        background: #fff;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: grab;
        border-left: 4px solid;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.2s ease;
    }
    
    .kanban-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    
    .kanban-card:active {
        cursor: grabbing;
        transform: scale(1.02);
    }
    
    .kanban-card .job-id {
        font-weight: 700;
        color: #3b82f6;
        font-size: 0.9375rem;
        transition: color 0.15s ease;
    }
    
    .kanban-card .job-id:hover {
        color: #1d4ed8;
    }
    
    .kanban-card .customer-name {
        font-size: 0.9375rem;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .kanban-card .asset-info {
        font-size: 0.8125rem;
        color: #64748b;
        display: flex;
        align-items: center;
        margin-top: 0.25rem;
    }
    
    .kanban-card .asset-info i {
        font-size: 0.75rem;
        margin-right: 0.5rem;
        color: #94a3b8;
    }
    
    .warranty-badge {
        font-size: 0.6875rem;
        font-weight: 700;
        padding: 0.125rem 0.5rem;
    }
    
    /* Column Colors */
    .kanban-column[data-status="new_checkin"] .kanban-column-header { 
        border-color: #6b7280;
        color: #374151;
    }
    .kanban-column[data-status="pending_repair"] .kanban-column-header { 
        border-color: #f59e0b;
        color: #92400e;
    }
    .kanban-column[data-status="in_progress"] .kanban-column-header { 
        border-color: #3b82f6;
        color: #1e40af;
    }
    .kanban-column[data-status="repair_done"] .kanban-column-header { 
        border-color: #10b981;
        color: #065f46;
    }
    .kanban-column[data-status="ready_handover"] .kanban-column-header { 
        border-color: #8b5cf6;
        color: #5b21b6;
    }
    
    .kanban-card[data-status="new_checkin"] { border-left-color: #6b7280; }
    .kanban-card[data-status="pending_repair"] { border-left-color: #f59e0b; }
    .kanban-card[data-status="in_progress"] { border-left-color: #3b82f6; }
    .kanban-card[data-status="repair_done"] { border-left-color: #10b981; }
    .kanban-card[data-status="ready_handover"] { border-left-color: #8b5cf6; }
    
    /* Sortable States */
    .sortable-ghost {
        opacity: 0.4;
        background: #e5e7eb;
    }
    
    .sortable-drag {
        opacity: 1;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        transform: rotate(2deg);
    }
    
    .sortable-chosen {
        background: #f0f9ff;
    }
    
    /* Empty Column State */
    .kanban-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }
    
    .kanban-empty i {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }
    
    @media (max-width: 991.98px) {
        .kanban-board {
            flex-direction: column;
        }
        
        .kanban-column {
            flex: 0 0 auto;
            max-height: 400px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <h1 class="page-title mb-0">
        <i class="bi bi-kanban"></i>
        <?= lang('App.kanbanBoard') ?>
    </h1>
    <div class="d-flex gap-2">
        <a href="<?= base_url('jobs') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-list me-1"></i><?= lang('App.allJobs') ?>
        </a>
        <a href="<?= base_url('jobs/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i><?= lang('App.newJob') ?>
        </a>
    </div>
</div>

<!-- Kanban Board -->
<div class="kanban-board">
    <?php
    $statuses = [
        'new_checkin' => ['name' => lang('App.statusNewCheckin'), 'color' => 'secondary', 'icon' => 'inbox'],
        'pending_repair' => ['name' => lang('App.statusPendingRepair'), 'color' => 'warning', 'icon' => 'hourglass-split'],
        'in_progress' => ['name' => lang('App.statusInProgress'), 'color' => 'primary', 'icon' => 'gear'],
        'repair_done' => ['name' => lang('App.statusRepairDone'), 'color' => 'success', 'icon' => 'check-circle'],
        'ready_handover' => ['name' => lang('App.statusReadyHandover'), 'color' => 'info', 'icon' => 'box-seam'],
    ];
    ?>
    
    <?php foreach ($statuses as $statusKey => $statusInfo): ?>
        <div class="kanban-column" data-status="<?= $statusKey ?>">
            <div class="kanban-column-header">
                <span>
                    <i class="bi bi-<?= $statusInfo['icon'] ?> me-2"></i>
                    <?= $statusInfo['name'] ?>
                </span>
                <span class="badge bg-<?= $statusInfo['color'] ?> kanban-count">
                    <?= count($jobsByStatus[$statusKey] ?? []) ?>
                </span>
            </div>
            <div class="kanban-column-body" id="kanban-<?= $statusKey ?>">
                <?php if (empty($jobsByStatus[$statusKey])): ?>
                    <div class="kanban-empty">
                        <i class="bi bi-inbox"></i>
                        No jobs
                    </div>
                <?php else: ?>
                    <?php foreach ($jobsByStatus[$statusKey] as $job): ?>
                        <div class="kanban-card" data-status="<?= $statusKey ?>" data-id="<?= $job['id'] ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="job-id text-decoration-none">
                                    <?= esc($job['job_id']) ?>
                                </a>
                                <?php if ($job['is_warranty_claim']): ?>
                                    <span class="badge bg-danger warranty-badge" title="Warranty Claim">
                                        <i class="bi bi-shield-check"></i> W
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="customer-name"><?= esc($job['customer_name']) ?></div>
                            <div class="asset-info">
                                <i class="bi bi-cpu"></i>
                                <?= esc($job['brand_model']) ?>
                            </div>
                            <div class="asset-info">
                                <i class="bi bi-upc"></i>
                                <?= esc($job['serial_number']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kanban is initialized by app.js
    console.log('Technician Dashboard loaded');
});
</script>
<?= $this->endSection() ?>
