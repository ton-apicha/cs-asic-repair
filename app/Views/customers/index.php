<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-people me-2"></i><?= lang('App.customers') ?></h1>
        <a href="<?= base_url('customers/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i><?= lang('App.newCustomer') ?>
        </a>
    </div>

    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <input type="text" class="form-control" id="searchInput" placeholder="<?= lang('App.searchCustomer') ?>" onkeyup="searchTable()">
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="customersTable">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.name') ?></th>
                            <th><?= lang('App.phone') ?></th>
                            <th class="d-none d-md-table-cell"><?= lang('App.email') ?></th>
                            <th class="d-none d-lg-table-cell"><?= lang('App.createdAt') ?></th>
                            <th><?= lang('App.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4"><?= lang('App.noCustomersFound') ?></td></tr>
                        <?php else: ?>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('customers/view/' . $customer['id']) ?>" class="text-decoration-none fw-semibold">
                                            <?= esc($customer['name']) ?>
                                        </a>
                                        <!-- Show email on mobile below name -->
                                        <div class="d-md-none small text-muted mt-1">
                                            <?php if ($customer['email']): ?>
                                                <i class="bi bi-envelope me-1"></i><?= esc($customer['email']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?= esc($customer['phone']) ?>
                                        <!-- Show created date on mobile below phone -->
                                        <div class="d-lg-none small text-muted mt-1">
                                            <i class="bi bi-calendar me-1"></i><?= date('d/m/Y', strtotime($customer['created_at'])) ?>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell"><?= $customer['email'] ? esc($customer['email']) : '<span class="text-muted">-</span>' ?></td>
                                    <td class="d-none d-lg-table-cell text-muted small"><?= date('d/m/Y', strtotime($customer['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('customers/view/' . $customer['id']) ?>" class="btn btn-outline-primary" title="<?= lang('App.view') ?>">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= base_url('customers/edit/' . $customer['id']) ?>" class="btn btn-outline-secondary" title="<?= lang('App.edit') ?>">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?= base_url('jobs/create') ?>?customer_id=<?= $customer['id'] ?>" class="btn btn-outline-success" title="<?= lang('App.newJob') ?>">
                                                <i class="bi bi-plus"></i>
                                            </a>
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
function searchTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const table = document.getElementById('customersTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    }
}
</script>
<?= $this->endSection() ?>