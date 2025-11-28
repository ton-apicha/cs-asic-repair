<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-person<?= $user ? '-gear' : '-plus' ?> me-2"></i>
                    <?= $user ? lang('App.editUser') : lang('App.newUser') ?>
                </h1>
                <a href="<?= base_url('settings/users') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
                </a>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?= $user ? base_url('settings/users/update/' . $user['id']) : base_url('settings/users/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <!-- Account Info -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-key me-2"></i><?= lang('App.login') ?>
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required"><?= lang('App.username') ?></label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="username" 
                                           value="<?= old('username', $user['username'] ?? '') ?>"
                                           <?= $user ? 'readonly' : 'required' ?>
                                           minlength="3"
                                           placeholder="<?= lang('App.enterUsername') ?>">
                                    <?php if ($user): ?>
                                    <div class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i><?= lang('App.usernameCannotChange') ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label <?= $user ? '' : 'required' ?>"><?= lang('App.password') ?></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               name="password" 
                                               id="password"
                                               <?= $user ? '' : 'required' ?>
                                               minlength="6"
                                               placeholder="<?= lang('App.enterPassword') ?>">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                            <i class="bi bi-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    <?php if ($user): ?>
                                    <div class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i><?= lang('App.leaveBlankPassword') ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Personal Info -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-person-vcard me-2"></i><?= lang('App.customerDetails') ?>
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label required"><?= lang('App.name') ?></label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="name" 
                                           value="<?= old('name', $user['name'] ?? '') ?>"
                                           required
                                           minlength="2"
                                           placeholder="<?= lang('App.name') ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= lang('App.email') ?></label>
                                    <input type="email" 
                                           class="form-control" 
                                           name="email" 
                                           value="<?= old('email', $user['email'] ?? '') ?>"
                                           placeholder="example@email.com">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= lang('App.phone') ?></label>
                                    <input type="tel" 
                                           class="form-control" 
                                           name="phone" 
                                           value="<?= old('phone', $user['phone'] ?? '') ?>"
                                           placeholder="08x-xxx-xxxx">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Role & Branch -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-shield-check me-2"></i><?= lang('App.userRole') ?> & <?= lang('App.branch') ?>
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required"><?= lang('App.userRole') ?></label>
                                    <select class="form-select" name="role" required>
                                        <option value="technician" <?= old('role', $user['role'] ?? '') === 'technician' ? 'selected' : '' ?>>
                                            <?= lang('App.roleTechnician') ?>
                                        </option>
                                        <option value="admin" <?= old('role', $user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>
                                            <?= lang('App.roleAdmin') ?>
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= lang('App.branch') ?></label>
                                    <select class="form-select" name="branch_id">
                                        <option value=""><?= lang('App.all') ?> (<?= lang('App.branches') ?>)</option>
                                        <?php foreach ($branches as $branch): ?>
                                        <option value="<?= $branch['id'] ?>" <?= old('branch_id', $user['branch_id'] ?? '') == $branch['id'] ? 'selected' : '' ?>>
                                            <?= esc($branch['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status (only for edit) -->
                        <?php if ($user): ?>
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-toggles me-2"></i><?= lang('App.status') ?>
                            </h5>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       role="switch" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       <?= old('is_active', $user['is_active'] ?? 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">
                                    <?= lang('App.active') ?>
                                </label>
                            </div>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i><?= lang('App.userStatusNote') ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between border-top pt-4">
                            <?php if ($user): ?>
                            <button type="button" class="btn btn-outline-danger" onclick="deleteUser()">
                                <i class="bi bi-trash me-1"></i><?= lang('App.delete') ?>
                            </button>
                            <?php else: ?>
                            <div></div>
                            <?php endif; ?>
                            
                            <div>
                                <a href="<?= base_url('settings/users') ?>" class="btn btn-secondary me-2">
                                    <?= lang('App.cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i><?= lang('App.save') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php if ($user && $user['last_login']): ?>
            <div class="card mt-3">
                <div class="card-body py-2">
                    <small class="text-muted">
                        <i class="bi bi-clock-history me-1"></i>
                        <?= lang('App.lastLogin') ?>: <?= date('d/m/Y H:i', strtotime($user['last_login'])) ?>
                    </small>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($user): ?>
<!-- Delete Form -->
<form id="deleteForm" action="<?= base_url('settings/users/delete/' . $user['id']) ?>" method="POST" class="d-none">
    <?= csrf_field() ?>
</form>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}

<?php if ($user): ?>
function deleteUser() {
    if (confirm('<?= lang('App.confirmDelete') ?>\n\n<?= lang('App.user') ?>: <?= esc($user['name']) ?>')) {
        document.getElementById('deleteForm').submit();
    }
}
<?php endif; ?>
</script>
<?= $this->endSection() ?>

