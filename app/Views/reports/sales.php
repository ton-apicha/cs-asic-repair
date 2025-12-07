<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-graph-up me-2"></i><?= lang('App.salesReport') ?>
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
            <div class="col-md-3">
                <label class="form-label"><?= lang('App.date') ?> (<?= lang('App.fromDate') ?>)</label>
                <input type="date" class="form-control" name="from" value="<?= $from ?? date('Y-m-01') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label"><?= lang('App.date') ?> (<?= lang('App.toDate') ?>)</label>
                <input type="date" class="form-control" name="to" value="<?= $to ?? date('Y-m-d') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label"><?= lang('App.status') ?></label>
                <select class="form-select" name="status">
                    <option value=""><?= lang('App.all') ?></option>
                    <option value="delivered" <?= ($status ?? '') === 'delivered' ? 'selected' : '' ?>><?= lang('App.statusDelivered') ?></option>
                    <option value="ready_handover" <?= ($status ?? '') === 'ready_handover' ? 'selected' : '' ?>><?= lang('App.statusReadyHandover') ?></option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i><?= lang('App.filter') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= lang('App.totalJobs') ?></h6>
                        <h3 class="mb-0"><?= $summary['total_jobs'] ?? 0 ?></h3>
                    </div>
                    <i class="bi bi-clipboard-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= lang('App.totalRevenue') ?></h6>
                        <h3 class="mb-0">฿<?= number_format($summary['total_revenue'] ?? 0, 0) ?></h3>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= lang('App.laborIncome') ?></h6>
                        <h3 class="mb-0">฿<?= number_format($summary['labor_income'] ?? 0, 0) ?></h3>
                    </div>
                    <i class="bi bi-tools fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= lang('App.partsIncome') ?></h6>
                        <h3 class="mb-0">฿<?= number_format($summary['parts_income'] ?? 0, 0) ?></h3>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-table me-2"></i><?= lang('App.salesDetails') ?>
    </div>
    <div class="card-body p-0">
        <?php if (empty($jobs)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox d-block fs-1 mb-2 opacity-50"></i>
                <p class="mb-0"><?= lang('App.noRecords') ?></p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.date') ?></th>
                            <th><?= lang('App.jobId') ?></th>
                            <th><?= lang('App.customer') ?></th>
                            <th><?= lang('App.asset') ?></th>
                            <th class="text-end"><?= lang('App.laborCost') ?></th>
                            <th class="text-end"><?= lang('App.partsCost') ?></th>
                            <th class="text-end"><?= lang('App.grandTotal') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($job['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('jobs/view/' . $job['id']) ?>"><?= esc($job['job_id']) ?></a>
                                </td>
                                <td><?= esc($job['customer_name']) ?></td>
                                <td><?= esc($job['brand_model']) ?></td>
                                <td class="text-end">฿<?= number_format($job['labor_cost'], 0) ?></td>
                                <td class="text-end">฿<?= number_format($job['parts_cost'], 0) ?></td>
                                <td class="text-end fw-bold">฿<?= number_format($job['grand_total'], 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4" class="text-end"><?= lang('App.total') ?>:</th>
                            <th class="text-end">฿<?= number_format($summary['labor_income'] ?? 0, 0) ?></th>
                            <th class="text-end">฿<?= number_format($summary['parts_income'] ?? 0, 0) ?></th>
                            <th class="text-end">฿<?= number_format($summary['total_revenue'] ?? 0, 0) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>