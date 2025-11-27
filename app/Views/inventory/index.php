<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-box-seam me-2"></i><?= lang('App.inventory') ?></h1>
        <?php if ($isAdmin ?? false): ?>
            <a href="<?= base_url('inventory/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i><?= lang('App.newPart') ?>
            </a>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label">Category:</label>
                </div>
                <div class="col-auto">
                    <select class="form-select" id="categoryFilter" onchange="filterByCategory(this.value)">
                        <option value=""><?= lang('App.all') ?></option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                            <option value="<?= esc($cat['category']) ?>" <?= $currentCategory === $cat['category'] ? 'selected' : '' ?>>
                                <?= esc($cat['category']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control" id="searchInput" placeholder="<?= lang('App.searchPart') ?>" onkeyup="searchTable()">
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('inventory/low-stock') ?>" class="btn btn-outline-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i><?= lang('App.lowStock') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Parts Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="partsTable">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.partCode') ?></th>
                            <th><?= lang('App.partName') ?></th>
                            <th><?= lang('App.category') ?></th>
                            <th class="text-end"><?= lang('App.partCostPrice') ?></th>
                            <th class="text-end"><?= lang('App.partSellPrice') ?></th>
                            <th class="text-center"><?= lang('App.quantity') ?></th>
                            <th><?= lang('App.partLocation') ?></th>
                            <th><?= lang('App.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($parts)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4"><?= lang('App.noPartsFound') ?></td></tr>
                        <?php else: ?>
                            <?php foreach ($parts as $part): ?>
                                <tr class="<?= $part['quantity'] <= $part['reorder_point'] ? 'table-warning' : '' ?>">
                                    <td class="fw-semibold"><?= esc($part['part_code']) ?></td>
                                    <td>
                                        <a href="<?= base_url('inventory/view/' . $part['id']) ?>" class="text-decoration-none">
                                            <?= esc($part['name']) ?>
                                        </a>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= esc($part['category'] ?? '-') ?></span></td>
                                    <td class="text-end">฿<?= number_format($part['cost_price'], 2) ?></td>
                                    <td class="text-end">฿<?= number_format($part['sell_price'], 2) ?></td>
                                    <td class="text-center">
                                        <?php if ($part['quantity'] <= 0): ?>
                                            <span class="badge bg-danger"><?= $part['quantity'] ?></span>
                                        <?php elseif ($part['quantity'] <= $part['reorder_point']): ?>
                                            <span class="badge bg-warning text-dark"><?= $part['quantity'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><?= $part['quantity'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?= esc($part['location'] ?? '-') ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('inventory/view/' . $part['id']) ?>" class="btn btn-outline-primary" title="<?= lang('App.view') ?>">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($isAdmin ?? false): ?>
                                                <a href="<?= base_url('inventory/edit/' . $part['id']) ?>" class="btn btn-outline-secondary" title="<?= lang('App.edit') ?>">
                                                    <i class="bi bi-pencil"></i>
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
function filterByCategory(category) {
    const url = new URL(window.location.href);
    if (category) {
        url.searchParams.set('category', category);
    } else {
        url.searchParams.delete('category');
    }
    window.location.href = url.toString();
}

function searchTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const table = document.getElementById('partsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    }
}
</script>
<?= $this->endSection() ?>

