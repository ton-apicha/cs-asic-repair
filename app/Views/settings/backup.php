<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="bi bi-database text-primary me-2"></i>
        Backup & Restore
    </h1>
    <a href="<?= base_url('settings') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Settings
    </a>
</div>

<div class="row">
    <div class="col-lg-6">
        <!-- Create Backup -->
        <div class="card mb-4 hover-lift">
            <div class="card-header">
                <i class="bi bi-cloud-arrow-up me-1"></i>Create Backup
            </div>
            <div class="card-body">
                <p class="text-muted">Create a complete backup of your database including all customers, jobs, inventory, and settings.</p>
                
                <form action="<?= base_url('settings/backup/create') ?>" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Create Backup Now
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Restore Backup -->
        <div class="card mb-4 hover-lift">
            <div class="card-header">
                <i class="bi bi-cloud-arrow-down me-1"></i>Restore from Backup
            </div>
            <div class="card-body">
                <p class="text-muted">Restore your database from a backup file. <strong class="text-danger">Warning: This will overwrite all current data!</strong></p>
                
                <form action="<?= base_url('settings/backup/restore') ?>" method="POST" enctype="multipart/form-data" 
                      onsubmit="return confirm('Are you sure you want to restore this backup? All current data will be overwritten!')">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Upload Backup File</label>
                        <input type="file" class="form-control" name="backup_file" accept=".sql,.sql.gz">
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Restore Backup
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <!-- Backup List -->
        <div class="card hover-lift">
            <div class="card-header">
                <i class="bi bi-archive me-1"></i>Available Backups
            </div>
            <div class="card-body p-0">
                <?php if (empty($backups)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-database fs-1 d-block mb-2 opacity-50"></i>
                    <p>No backups found</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Size</th>
                                <th>Created</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($backups as $backup): ?>
                            <tr>
                                <td>
                                    <i class="bi <?= $backup['compressed'] ? 'bi-file-zip' : 'bi-file-text' ?> me-1"></i>
                                    <?= esc($backup['filename']) ?>
                                </td>
                                <td>
                                    <?php
                                    $size = $backup['size'];
                                    if ($size > 1048576) {
                                        echo number_format($size / 1048576, 2) . ' MB';
                                    } elseif ($size > 1024) {
                                        echo number_format($size / 1024, 2) . ' KB';
                                    } else {
                                        echo $size . ' B';
                                    }
                                    ?>
                                </td>
                                <td><?= date('d M Y, H:i', strtotime($backup['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('settings/backup/download/' . urlencode($backup['filename'])) ?>" 
                                           class="btn btn-outline-primary" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <form action="<?= base_url('settings/backup/restore') ?>" method="POST" class="d-inline"
                                              onsubmit="return confirm('Restore this backup? Current data will be overwritten!')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="filename" value="<?= esc($backup['filename']) ?>">
                                            <button type="submit" class="btn btn-outline-warning" title="Restore">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('settings/backup/delete/' . urlencode($backup['filename'])) ?>" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this backup?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tips -->
<div class="card mt-4">
    <div class="card-header">
        <i class="bi bi-lightbulb me-1"></i>Backup Tips
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li>Create backups regularly, especially before making major changes.</li>
            <li>Download and store backups in a safe location (cloud storage, external drive).</li>
            <li>Compressed (.gz) backups use less storage space.</li>
            <li>Test your backups periodically by restoring to a test environment.</li>
            <li>Keep at least 3 recent backups at all times.</li>
        </ul>
    </div>
</div>
<?= $this->endSection() ?>

