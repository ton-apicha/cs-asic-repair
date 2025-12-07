<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-piggy-bank me-2"></i><?= lang('App.profitReport') ?>
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
                <label class="form-label"><?= lang('App.date') ?> (<?= lang('App.fromDate') ?>)</label>
                <input type="date" class="form-control" name="from" value="<?= $from ?? date('Y-m-01') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label"><?= lang('App.date') ?> (<?= lang('App.toDate') ?>)</label>
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
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= lang('App.totalRevenue') ?></h6>
                        <h3 class="mb-0">฿<?= number_format($summary['revenue'] ?? 0, 0) ?></h3>
                    </div>
                    <i class="bi bi-cash-stack fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= lang('App.totalCost') ?></h6>
                        <h3 class="mb-0">฿<?= number_format($summary['cost'] ?? 0, 0) ?></h3>
                    </div>
                    <i class="bi bi-wallet2 fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= lang('App.netProfit') ?></h6>
                        <h3 class="mb-0">฿<?= number_format($summary['profit'] ?? 0, 0) ?></h3>
                    </div>
                    <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= lang('App.profitMargin') ?></h6>
                        <h3 class="mb-0"><?= number_format($summary['margin'] ?? 0, 1) ?>%</h3>
                    </div>
                    <i class="bi bi-percent fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profit Details -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-arrow-up-circle me-2 text-success"></i><?= lang('App.totalRevenue') ?>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><?= lang('App.laborIncome') ?></td>
                        <td class="text-end fw-bold">฿<?= number_format($summary['labor_income'] ?? 0, 0) ?></td>
                    </tr>
                    <tr>
                        <td><?= lang('App.partsSales') ?></td>
                        <td class="text-end fw-bold">฿<?= number_format($summary['parts_sales'] ?? 0, 0) ?></td>
                    </tr>
                    <tr>
                        <td><?= lang('App.vatCollected') ?></td>
                        <td class="text-end fw-bold">฿<?= number_format($summary['vat_collected'] ?? 0, 0) ?></td>
                    </tr>
                    <tr class="table-success">
                        <th><?= lang('App.totalRevenue') ?></th>
                        <th class="text-end">฿<?= number_format($summary['revenue'] ?? 0, 0) ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-arrow-down-circle me-2 text-danger"></i><?= lang('App.totalCost') ?>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><?= lang('App.partsCost') ?></td>
                        <td class="text-end fw-bold">฿<?= number_format($summary['parts_cost'] ?? 0, 0) ?></td>
                    </tr>
                    <tr>
                        <td><?= lang('App.warrantyClaims') ?></td>
                        <td class="text-end fw-bold">฿<?= number_format($summary['warranty_cost'] ?? 0, 0) ?></td>
                    </tr>
                    <tr class="table-danger">
                        <th><?= lang('App.totalCost') ?></th>
                        <th class="text-end">฿<?= number_format($summary['cost'] ?? 0, 0) ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>