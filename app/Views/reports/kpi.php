<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-graph-up me-2"></i><?= lang('App.kpiReport') ?></h1>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- Today's Revenue -->
        <div class="col-md-6 col-xl-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1"><?= lang('App.todayRevenue') ?></h6>
                            <h2 class="mb-0">฿<?= number_format($todayRevenue, 0) ?></h2>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="col-md-6 col-xl-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1"><?= lang('App.monthlyRevenue') ?></h6>
                            <h2 class="mb-0">฿<?= number_format($monthlyRevenue, 0) ?></h2>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- First Time Fix Rate -->
        <div class="col-md-6 col-xl-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1"><?= lang('App.firstTimeFixRate') ?></h6>
                            <h2 class="mb-0"><?= number_format($ftfr, 1) ?>%</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Repair Time -->
        <div class="col-md-6 col-xl-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50 mb-1"><?= lang('App.avgRepairTime') ?></h6>
                            <h2 class="mb-0"><?= number_format($avgRepairTime, 1) ?> <?= lang('App.hours') ?></h2>
                        </div>
                        <i class="bi bi-clock-history fs-1 text-dark-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="display-4 fw-bold text-primary"><?= $jobStats['today'] ?? 0 ?></h3>
                    <p class="text-muted mb-0"><?= lang('App.todayJobs') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="display-4 fw-bold text-info"><?= $jobStats['monthly'] ?? 0 ?></h3>
                    <p class="text-muted mb-0"><?= lang('App.monthlyJobs') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="display-4 fw-bold text-warning"><?= $jobStats['pending'] ?? 0 ?></h3>
                    <p class="text-muted mb-0"><?= lang('App.pendingJobs') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="display-4 fw-bold text-success"><?= $jobStats['completed'] ?? 0 ?></h3>
                    <p class="text-muted mb-0"><?= lang('App.completedJobs') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional KPIs -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header"><?= lang('App.claimRate') ?></div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-danger" style="width: <?= min($claimRate, 100) ?>%;">
                                    <?= number_format($claimRate, 1) ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0">
                        <small><?= lang('App.claimRate') ?> (<?= lang('App.lowIsBetter') ?? 'ยิ่งต่ำยิ่งดี' ?>)</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header"><?= lang('App.inventoryValue') ?></div>
                <div class="card-body text-center">
                    <h2 class="display-5 fw-bold text-primary">฿<?= number_format($inventoryValue, 0) ?></h2>
                    <p class="text-muted mb-0"><?= lang('App.inventoryValue') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>