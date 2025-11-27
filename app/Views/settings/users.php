<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-people me-2"></i><?= lang('App.users') ?></h1>
        <a href="<?= base_url('settings/users/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i><?= lang('App.newUser') ?>
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('App.username') ?></th>
                        <th><?= lang('App.name') ?></th>
                        <th><?= lang('App.userRole') ?></th>
                        <th><?= lang('App.branch') ?></th>
                        <th><?= lang('App.status') ?></th>
                        <th><?= lang('App.lastLogin') ?></th>
                        <th><?= lang('App.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="fw-semibold"><?= esc($u['username']) ?></td>
                            <td><?= esc($u['name']) ?></td>
                            <td>
                                <span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : 'info' ?>">
                                    <?= $u['role'] === 'admin' ? lang('App.roleAdmin') : lang('App.roleTechnician') ?>
                                </span>
                            </td>
                            <td><?= esc($u['branch_name'] ?? '-') ?></td>
                            <td>
                                <span class="badge bg-<?= $u['is_active'] ? 'success' : 'secondary' ?>">
                                    <?= $u['is_active'] ? lang('App.active') : lang('App.inactive') ?>
                                </span>
                            </td>
                            <td class="text-muted small">
                                <?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : '-' ?>
                            </td>
                            <td>
                                <a href="<?= base_url('settings/users/edit/' . $u['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

