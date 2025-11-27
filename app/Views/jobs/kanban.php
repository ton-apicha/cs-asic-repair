<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-kanban me-2"></i><?= lang('App.kanbanBoard') ?></h1>
        <div>
            <a href="<?= base_url('jobs') ?>" class="btn btn-outline-secondary me-2">
                <i class="bi bi-list-ul me-1"></i><?= lang('App.allJobs') ?>
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
            'new_checkin' => ['name' => lang('App.statusNewCheckin'), 'color' => 'secondary'],
            'pending_repair' => ['name' => lang('App.statusPendingRepair'), 'color' => 'warning'],
            'in_progress' => ['name' => lang('App.statusInProgress'), 'color' => 'info'],
            'repair_done' => ['name' => lang('App.statusRepairDone'), 'color' => 'success'],
            'ready_handover' => ['name' => lang('App.statusReadyHandover'), 'color' => 'primary'],
        ];
        ?>
        
        <?php foreach ($statuses as $statusKey => $statusInfo): ?>
            <div class="kanban-column" data-status="<?= $statusKey ?>">
                <div class="kanban-column-header" style="border-color: var(--bs-<?= $statusInfo['color'] ?>);">
                    <span><?= $statusInfo['name'] ?></span>
                    <span class="badge bg-<?= $statusInfo['color'] ?> kanban-count"><?= count($jobsByStatus[$statusKey] ?? []) ?></span>
                </div>
                <div class="kanban-column-body" id="kanban-<?= $statusKey ?>">
                    <?php if (!empty($jobsByStatus[$statusKey])): ?>
                        <?php foreach ($jobsByStatus[$statusKey] as $job): ?>
                            <div class="kanban-card" data-status="<?= $statusKey ?>" data-job-id="<?= $job['id'] ?>">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="job-id text-decoration-none">
                                        <?= esc($job['job_id']) ?>
                                    </a>
                                    <?php if ($job['is_warranty_claim']): ?>
                                        <span class="badge bg-danger" title="Warranty Claim">W</span>
                                    <?php endif; ?>
                                </div>
                                <div class="customer-name mb-1"><?= esc($job['customer_name']) ?></div>
                                <div class="asset-info">
                                    <i class="bi bi-hdd-rack me-1"></i><?= esc($job['brand_model']) ?>
                                </div>
                                <div class="asset-info">
                                    <i class="bi bi-upc me-1"></i><?= esc($job['serial_number']) ?>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i><?= date('d/m H:i', strtotime($job['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize Kanban with SortableJS
    document.querySelectorAll('.kanban-column-body').forEach(function(column) {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                var jobId = evt.item.dataset.jobId;
                var newStatus = evt.to.closest('.kanban-column').dataset.status;
                var oldStatus = evt.from.closest('.kanban-column').dataset.status;
                
                if (newStatus !== oldStatus) {
                    updateJobStatus(jobId, newStatus, evt);
                }
                updateCounts();
            }
        });
    });
});

function updateJobStatus(jobId, newStatus, evt) {
    $.post('<?= base_url('jobs/update-status/') ?>' + jobId, {
        status: newStatus,
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            App.showToast(response.message || 'สถานะถูกอัปเดตแล้ว', 'success');
        } else {
            // Revert
            evt.from.appendChild(evt.item);
            App.showToast(response.message || 'ไม่สามารถอัปเดตสถานะได้', 'danger');
        }
        updateCounts();
    }).fail(function() {
        evt.from.appendChild(evt.item);
        App.showToast('เกิดข้อผิดพลาด', 'danger');
        updateCounts();
    });
}

function updateCounts() {
    document.querySelectorAll('.kanban-column').forEach(function(column) {
        var count = column.querySelectorAll('.kanban-card').length;
        column.querySelector('.kanban-count').textContent = count;
    });
}
</script>
<?= $this->endSection() ?>

