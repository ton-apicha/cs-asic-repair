<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-hdd-rack me-2"></i><?= lang('App.assets') ?></h1>
        <a href="<?= base_url('machines/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i><?= lang('App.newAsset') ?>
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label"><?= lang('App.status') ?>:</label>
                </div>
                <div class="col-auto">
                    <select class="form-select" id="statusFilter" onchange="filterByStatus(this.value)">
                        <option value=""><?= lang('App.all') ?></option>
                        <option value="stored" <?= $currentStatus === 'stored' ? 'selected' : '' ?>><?= lang('App.assetStatusStored') ?></option>
                        <option value="repairing" <?= $currentStatus === 'repairing' ? 'selected' : '' ?>><?= lang('App.assetStatusRepairing') ?></option>
                        <option value="repaired" <?= $currentStatus === 'repaired' ? 'selected' : '' ?>><?= lang('App.assetStatusRepaired') ?></option>
                        <option value="returned" <?= $currentStatus === 'returned' ? 'selected' : '' ?>><?= lang('App.assetStatusReturned') ?></option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="searchInput" placeholder="<?= lang('App.searchAsset') ?>" onkeyup="searchTable()">
                </div>
            </div>
        </div>
    </div>

    <!-- Assets Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="assetsTable">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.assetSerialNumber') ?></th>
                            <th><?= lang('App.assetBrandModel') ?></th>
                            <th><?= lang('App.customer') ?></th>
                            <th><?= lang('App.status') ?></th>
                            <th><?= lang('App.createdAt') ?></th>
                            <th><?= lang('App.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assets)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4"><?= lang('App.noAssetsFound') ?></td></tr>
                        <?php else: ?>
                            <?php foreach ($assets as $asset): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('machines/view/' . $asset['id']) ?>" class="fw-semibold text-decoration-none">
                                            <?= esc($asset['serial_number']) ?>
                                        </a>
                                    </td>
                                    <td><?= esc($asset['brand_model']) ?></td>
                                    <td>
                                        <?= esc($asset['customer_name']) ?>
                                        <br><small class="text-muted"><?= esc($asset['customer_phone']) ?></small>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td class="text-muted small"><?= date('d/m/Y', strtotime($asset['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('machines/view/' . $asset['id']) ?>" class="btn btn-outline-primary" title="<?= lang('App.view') ?>">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= base_url('machines/edit/' . $asset['id']) ?>" class="btn btn-outline-secondary" title="<?= lang('App.edit') ?>">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if ($asset['status'] === 'stored'): ?>
                                                <a href="<?= base_url('jobs/create-from-asset/' . $asset['id']) ?>" class="btn btn-outline-success" title="<?= lang('App.createJobFromAsset') ?>">
                                                    <i class="bi bi-tools"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filterByStatus(status) {
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

function searchTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const table = document.getElementById('assetsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    }
}
</script>
<?= $this->endSection() ?>

