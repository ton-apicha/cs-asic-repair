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
    
    /* Widget Cards */
    .stat-card {
        background: var(--card-bg);
        border-radius: 1rem;
        padding: 1.5rem;
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.15s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.25s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, currentColor 0%, transparent 70%);
        opacity: 0.05;
        transform: translate(30%, -30%);
    }
    
    .stat-card.primary { color: #3b82f6; }
    .stat-card.info { color: #06b6d4; }
    .stat-card.warning { color: #f59e0b; }
    .stat-card.success { color: #10b981; }
    
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-card.primary .stat-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-card.info .stat-icon { background: rgba(6, 182, 212, 0.1); color: #06b6d4; }
    .stat-card.warning .stat-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-card.success .stat-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    
    .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
        margin-top: 1rem;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }
    
    /* Chart Card */
    .chart-card {
        background: var(--card-bg);
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 0.3s;
        opacity: 0;
    }
    
    .chart-card-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .chart-card-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .chart-card-body {
        padding: 1.25rem;
    }

    /* Data Cards */
    .data-card {
        background: var(--card-bg);
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 0.3s;
        opacity: 0;
    }
    
    .data-card-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .data-card-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .data-card-title i {
        color: var(--text-secondary);
    }
    
    .data-card-body {
        padding: 0;
    }
    
    /* Revenue Card */
    .revenue-card {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 1rem;
        padding: 1.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 0.35s;
        opacity: 0;
    }
    
    .revenue-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
    }
    
    .revenue-value {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }
    
    .revenue-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }
    
    .revenue-period {
        font-size: 0.8125rem;
        opacity: 0.8;
        margin-top: 0.5rem;
    }
    
    /* Alert Card */
    .alert-card {
        background: var(--card-bg);
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        animation: fadeInUp 0.5s ease forwards;
        animation-delay: 0.4s;
        opacity: 0;
    }
    
    .alert-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .alert-card-title {
        font-weight: 600;
        font-size: 0.9375rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-badge {
        background: #fef3c7;
        color: #d97706;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
    }
    
    .low-stock-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        transition: background 0.15s ease;
    }
    
    .low-stock-item:last-child {
        border-bottom: none;
    }
    
    .low-stock-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .low-stock-info {
        flex: 1;
        min-width: 0;
    }
    
    .low-stock-name {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.875rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .low-stock-code {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    
    .low-stock-qty {
        background: #fef2f2;
        color: #dc2626;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        margin-left: 1rem;
    }
    
    /* Table Improvements */
    .jobs-table th {
        background: var(--table-stripe);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        padding: 0.875rem 1rem;
        border-color: var(--border-color);
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
    
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: var(--text-muted);
    }
    
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <h1 class="page-title mb-0">
        <i class="bi bi-speedometer2"></i>
        <?= lang('App.dashboard') ?>
    </h1>
    <div class="d-flex gap-2 align-items-center">
        <?php if (!empty($branches) && count($branches) > 1): ?>
        <select class="form-select form-select-sm" style="width: auto;" 
                onchange="window.location.href='?branch_id=' + this.value">
            <option value="">All Branches</option>
            <?php foreach ($branches as $branch): ?>
            <option value="<?= $branch['id'] ?>" <?= ($selectedBranch ?? null) == $branch['id'] ? 'selected' : '' ?>>
                <?= esc($branch['name']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <a href="<?= base_url('jobs/kanban') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-kanban me-1"></i>Kanban
        </a>
        <a href="<?= base_url('jobs/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i><?= lang('App.newJob') ?>
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Today's Jobs -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-value"><?= $jobStats['today'] ?? 0 ?></div>
            <div class="stat-label"><?= lang('App.todayJobs') ?></div>
        </div>
    </div>

    <!-- Monthly Jobs -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="bi bi-calendar3"></i>
            </div>
            <div class="stat-value"><?= $jobStats['monthly'] ?? 0 ?></div>
            <div class="stat-label"><?= lang('App.monthlyJobs') ?></div>
        </div>
    </div>

    <!-- Pending Jobs -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value"><?= $jobStats['pending'] ?? 0 ?></div>
            <div class="stat-label"><?= lang('App.pendingJobs') ?></div>
        </div>
    </div>

    <!-- Today's Revenue -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value">฿<?= number_format($todayRevenue ?? 0, 0) ?></div>
            <div class="stat-label"><?= lang('App.todayRevenue') ?></div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="chart-card">
            <div class="chart-card-header">
                <span class="chart-card-title">
                    <i class="bi bi-graph-up text-success"></i>
                    Revenue (Last 7 Days)
                </span>
            </div>
            <div class="chart-card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Job Status Chart -->
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="chart-card-header">
                <span class="chart-card-title">
                    <i class="bi bi-pie-chart text-primary"></i>
                    Job Status
                </span>
            </div>
            <div class="chart-card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Jobs -->
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <span class="data-card-title">
                    <i class="bi bi-clock-history"></i>
                    <?= lang('App.recentJobs') ?>
                </span>
                <a href="<?= base_url('jobs') ?>" class="btn btn-sm btn-outline-primary">
                    <?= lang('App.view') ?> <?= lang('App.all') ?>
                </a>
            </div>
            <div class="data-card-body">
                <?php if (empty($recentJobs)): ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p class="mb-0"><?= lang('App.noRecords') ?></p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table jobs-table mb-0">
                            <thead>
                                <tr>
                                    <th><?= lang('App.jobId') ?></th>
                                    <th><?= lang('App.customer') ?></th>
                                    <th><?= lang('App.status') ?></th>
                                    <th><?= lang('App.date') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentJobs as $job): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="job-link">
                                                <?= esc($job['job_id']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><?= esc($job['customer_name']) ?></span>
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
                                        <td>
                                            <span class="text-muted"><?= date('d M Y, H:i', strtotime($job['created_at'])) ?></span>
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

    <!-- Side Cards -->
    <div class="col-lg-4">
        <!-- Monthly Revenue -->
        <div class="revenue-card mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="revenue-label">
                    <i class="bi bi-graph-up-arrow me-1"></i>
                    <?= lang('App.monthlyRevenue') ?>
                </span>
                <i class="bi bi-three-dots-vertical" style="opacity: 0.7;"></i>
            </div>
            <div class="revenue-value">฿<?= number_format($monthlyRevenue ?? 0, 0) ?></div>
            <div class="revenue-period"><?= date('F Y') ?></div>
        </div>

        <!-- Low Stock Alert -->
        <div class="alert-card">
            <div class="alert-card-header">
                <span class="alert-card-title">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                    <?= lang('App.lowStockAlert') ?>
                </span>
                <span class="alert-badge"><?= count($lowStock ?? []) ?></span>
            </div>
            <div class="alert-card-body">
                <?php if (empty($lowStock)): ?>
                    <div class="empty-state py-4">
                        <i class="bi bi-check-circle text-success"></i>
                        <p class="mb-0">All stock levels OK</p>
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($lowStock, 0, 5) as $part): ?>
                        <div class="low-stock-item">
                            <div class="low-stock-info">
                                <div class="low-stock-name"><?= esc($part['name']) ?></div>
                                <div class="low-stock-code"><?= esc($part['part_code']) ?></div>
                            </div>
                            <span class="low-stock-qty"><?= $part['quantity'] ?> left</span>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (count($lowStock) > 5): ?>
                        <div class="p-3 text-center border-top">
                            <a href="<?= base_url('inventory/low-stock') ?>" class="btn btn-sm btn-outline-warning">
                                View All (<?= count($lowStock) ?>)
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart Data from PHP
    const revenueData = <?= json_encode($revenueChart ?? []) ?>;
    
    // Revenue Line Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.date),
                datasets: [{
                    label: 'Revenue (฿)',
                    data: revenueData.map(item => item.amount),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '฿' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            color: '#64748b',
                            callback: function(value) {
                                return '฿' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Job Status Pie Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const jobStats = {
            pending: <?= $jobStats['pending'] ?? 0 ?>,
            inProgress: <?= $jobStats['in_progress'] ?? 0 ?>,
            completed: <?= $jobStats['completed'] ?? 0 ?>,
            delivered: <?= $jobStats['delivered'] ?? 0 ?>
        };
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'In Progress', 'Completed', 'Delivered'],
                datasets: [{
                    data: [jobStats.pending, jobStats.inProgress, jobStats.completed, jobStats.delivered],
                    backgroundColor: [
                        '#f59e0b',
                        '#3b82f6',
                        '#10b981',
                        '#6366f1'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#64748b'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
