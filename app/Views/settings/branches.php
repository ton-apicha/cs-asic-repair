<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">
        <i class="bi bi-building me-2"></i><?= lang('App.branchManagement') ?>
    </h1>
    <button type="button" class="btn btn-primary" onclick="addBranch()">
        <i class="bi bi-plus-lg me-1"></i><?= lang('App.addBranch') ?>
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($branches)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-building d-block fs-1 mb-2 opacity-50"></i>
                <p class="mb-0">No branches configured</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th><?= lang('App.branchName') ?></th>
                            <th><?= lang('App.phone') ?></th>
                            <th><?= lang('App.address') ?></th>
                            <th><?= lang('App.status') ?></th>
                            <th><?= lang('App.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($branches as $branch): ?>
                        <tr>
                            <td><?= $branch['id'] ?></td>
                            <td>
                                <strong><?= esc($branch['name']) ?></strong>
                            </td>
                            <td><?= esc($branch['phone'] ?? '-') ?></td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?= esc($branch['address']) ?>">
                                    <?= esc($branch['address'] ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $branch['is_active'] ? 'success' : 'secondary' ?>">
                                    <?= $branch['is_active'] ? lang('App.active') : lang('App.inactive') ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="editBranch(<?= htmlspecialchars(json_encode($branch)) ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="deleteBranch(<?= $branch['id'] ?>, '<?= esc($branch['name']) ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Branch Modal -->
<div class="modal fade" id="branchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="branchForm" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="branchId">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="branchModalTitle">
                        <i class="bi bi-building me-2"></i><?= lang('App.addBranch') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required"><?= lang('App.branchName') ?></label>
                        <input type="text" class="form-control" name="name" id="branchName" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.phone') ?></label>
                        <input type="text" class="form-control" name="phone" id="branchPhone">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.address') ?></label>
                        <textarea class="form-control" name="address" id="branchAddress" rows="3"></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="branchActive" value="1" checked>
                        <label class="form-check-label" for="branchActive">
                            <?= lang('App.active') ?>
                        </label>
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
$(document).ready(function() {
    // Clean up any leftover backdrops
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').css('overflow', '');
    
    // Initialize modal using jQuery/Bootstrap
    var $branchModal = $('#branchModal');
    
    window.addBranch = function() {
        var $form = $('#branchForm');
        var $title = $('#branchModalTitle');
        
        $form[0].reset();
        $('#branchId').val('');
        $title.html('<i class="bi bi-building me-2"></i><?= lang('App.addBranch') ?>');
        $form.attr('action', '<?= base_url('settings/branches/store') ?>');
        $('#branchActive').prop('checked', true);
        
        $branchModal.modal('show');
    };
    
    window.editBranch = function(branch) {
        var $form = $('#branchForm');
        var $title = $('#branchModalTitle');
        
        $form[0].reset();
        $title.html('<i class="bi bi-pencil me-2"></i><?= lang('App.editBranch') ?>');
        $form.attr('action', '<?= base_url('settings/branches/update') ?>/' + branch.id);
        $('#branchId').val(branch.id);
        $('#branchName').val(branch.name);
        $('#branchPhone').val(branch.phone || '');
        $('#branchAddress').val(branch.address || '');
        $('#branchActive').prop('checked', branch.is_active == 1);
        
        $branchModal.modal('show');
    };
    
    window.deleteBranch = function(id, name) {
        if (confirm('<?= lang('App.confirmDelete') ?>\n\nBranch: ' + name)) {
            var $form = $('<form>', {
                method: 'POST',
                action: '<?= base_url('settings/branches/delete') ?>/' + id
            });
            
            $form.append($('<input>', {
                type: 'hidden',
                name: '<?= csrf_token() ?>',
                value: '<?= csrf_hash() ?>'
            }));
            
            $('body').append($form);
            $form.submit();
        }
    };
    
    // Cleanup on modal hide
    $branchModal.on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({'overflow': '', 'padding-right': ''});
    });
});
</script>
<?= $this->endSection() ?>

