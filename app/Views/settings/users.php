<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-people me-2"></i><?= lang('App.users') ?></h1>
        <button type="button" class="btn btn-primary" onclick="addUser()">
            <i class="bi bi-plus-lg me-1"></i><?= lang('App.newUser') ?>
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <?php if (empty($users)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-people d-block fs-1 mb-2 opacity-50"></i>
                    <p class="mb-0"><?= lang('App.noRecordsFound') ?></p>
                </div>
            <?php else: ?>
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
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick='editUser(<?= json_encode($u) ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="deleteUser(<?= $u['id'] ?>, '<?= esc($u['name']) ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="userForm" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id" id="userId">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">
                        <i class="bi bi-person-plus me-2"></i><?= lang('App.newUser') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Account Info -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-key me-2"></i><?= lang('App.login') ?>
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required"><?= lang('App.username') ?></label>
                                <input type="text" class="form-control" name="username" id="userName" required minlength="3">
                                <div class="form-text text-muted d-none" id="usernameNote">
                                    <i class="bi bi-info-circle me-1"></i><?= lang('App.usernameCannotChange') ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" id="passwordLabel"><?= lang('App.password') ?></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="userPassword" minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text text-muted d-none" id="passwordNote">
                                    <i class="bi bi-info-circle me-1"></i><?= lang('App.leaveBlankPassword') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Info -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-person-vcard me-2"></i><?= lang('App.customerDetails') ?>
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label required"><?= lang('App.name') ?></label>
                                <input type="text" class="form-control" name="name" id="userFullName" required minlength="2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= lang('App.email') ?></label>
                                <input type="email" class="form-control" name="email" id="userEmail">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= lang('App.phone') ?></label>
                                <input type="tel" class="form-control" name="phone" id="userPhone">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Role & Branch -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-shield-check me-2"></i><?= lang('App.userRole') ?> & <?= lang('App.branch') ?>
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required"><?= lang('App.userRole') ?></label>
                                <select class="form-select" name="role" id="userRole" required>
                                    <option value="technician"><?= lang('App.roleTechnician') ?></option>
                                    <option value="admin"><?= lang('App.roleAdmin') ?></option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= lang('App.branch') ?></label>
                                <select class="form-select" name="branch_id" id="userBranch">
                                    <option value=""><?= lang('App.all') ?> (<?= lang('App.branches') ?>)</option>
                                    <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status (only for edit mode) -->
                    <div class="mb-3 d-none" id="statusSection">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-toggles me-2"></i><?= lang('App.status') ?>
                        </h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="userActive" name="is_active" value="1" checked>
                            <label class="form-check-label" for="userActive"><?= lang('App.active') ?></label>
                        </div>
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i><?= lang('App.userStatusNote') ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('App.cancel') ?></button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i><?= lang('App.save') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var userModalInstance = null;

$(document).ready(function() {
    // Clean up any leftover backdrops on page load
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').css({'overflow': '', 'padding-right': ''});
    
    // Get the modal element and create Bootstrap modal instance
    var userModalEl = document.getElementById('userModal');
    userModalInstance = new bootstrap.Modal(userModalEl, {
        backdrop: true,
        keyboard: true
    });
    
    // Cleanup on modal hide
    userModalEl.addEventListener('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({'overflow': '', 'padding-right': ''});
    });
});

function addUser() {
    var $form = $('#userForm');
    var $title = $('#userModalTitle');
    
    $form[0].reset();
    $('#userId').val('');
    $title.html('<i class="bi bi-person-plus me-2"></i><?= lang('App.newUser') ?>');
    $form.attr('action', '<?= base_url('settings/users/store') ?>');
    
    // Show/hide elements for create mode
    $('#userName').prop('readonly', false);
    $('#usernameNote').addClass('d-none');
    $('#passwordLabel').addClass('required');
    $('#userPassword').prop('required', true);
    $('#passwordNote').addClass('d-none');
    $('#statusSection').addClass('d-none');
    $('#userActive').prop('checked', true);
    
    userModalInstance.show();
}

function editUser(user) {
    var $form = $('#userForm');
    var $title = $('#userModalTitle');
    
    $form[0].reset();
    $title.html('<i class="bi bi-person-gear me-2"></i><?= lang('App.editUser') ?>');
    $form.attr('action', '<?= base_url('settings/users/update') ?>/' + user.id);
    
    // Populate form
    $('#userId').val(user.id);
    $('#userName').val(user.username).prop('readonly', true);
    $('#userFullName').val(user.name);
    $('#userEmail').val(user.email || '');
    $('#userPhone').val(user.phone || '');
    $('#userRole').val(user.role);
    $('#userBranch').val(user.branch_id || '');
    $('#userActive').prop('checked', user.is_active == 1);
    
    // Show/hide elements for edit mode
    $('#usernameNote').removeClass('d-none');
    $('#passwordLabel').removeClass('required');
    $('#userPassword').prop('required', false);
    $('#passwordNote').removeClass('d-none');
    $('#statusSection').removeClass('d-none');
    
    userModalInstance.show();
}

function deleteUser(id, name) {
    if (confirm('<?= lang('App.confirmDelete') ?>\n\n<?= lang('App.user') ?>: ' + name)) {
        var $form = $('<form>', {
            method: 'POST',
            action: '<?= base_url('settings/users/delete') ?>/' + id
        });
        
        $form.append($('<input>', {
            type: 'hidden',
            name: '<?= csrf_token() ?>',
            value: '<?= csrf_hash() ?>'
        }));
        
        $('body').append($form);
        $form.submit();
    }
}

function togglePassword() {
    var $password = $('#userPassword');
    var $icon = $('#toggleIcon');
    
    if ($password.attr('type') === 'password') {
        $password.attr('type', 'text');
        $icon.removeClass('bi-eye').addClass('bi-eye-slash');
    } else {
        $password.attr('type', 'password');
        $icon.removeClass('bi-eye-slash').addClass('bi-eye');
    }
}
</script>
<?= $this->endSection() ?>
