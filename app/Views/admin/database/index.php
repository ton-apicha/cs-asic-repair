<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-database me-2"></i>Database Management</h1>
            <p class="text-muted mb-0">Monitor and optimize database performance</p>
        </div>
        <button class="btn btn-outline-primary" onclick="refreshStatus()">
            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
        </button>
    </div>

    <!-- Database Overview -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Database Size</h6>
                            <h3 class="mb-0"><?= number_format($status['database']['size_mb'], 2) ?> MB</h3>
                        </div>
                        <div class="text-primary fs-1">
                            <i class="bi bi-hdd"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Connections</h6>
                            <h3 class="mb-0"><?= $status['connections']['active'] ?> / <?= $status['connections']['max_connections'] ?></h3>
                        </div>
                        <div class="text-success fs-1">
                            <i class="bi bi-plug"></i>
                        </div>
                    </div>
                    <small class="text-muted">Active connections</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Tables</h6>
                            <h3 class="mb-0"><?= count($tables) ?></h3>
                        </div>
                        <div class="text-info fs-1">
                            <i class="bi bi-table"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">DB Uptime</h6>
                            <h3 class="mb-0"><?= $status['uptime']['days'] ?>d</h3>
                        </div>
                        <div class="text-warning fs-1">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                    <small class="text-muted"><?= $status['uptime']['formatted'] ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables & Quick Actions -->
    <div class="row g-4">
        <!-- Tables List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-table me-2"></i>Database Tables</h5>
                        <button class="btn btn-sm btn-primary" onclick="optimizeAllTables()">
                            <i class="bi bi-gear me-1"></i>Optimize All
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Table Name</th>
                                    <th>Rows</th>
                                    <th>Size (MB)</th>
                                    <th>Engine</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tables as $table): ?>
                                    <tr>
                                        <td><code><?= esc($table['table_name']) ?></code></td>
                                        <td><?= number_format($table['table_rows']) ?></td>
                                        <td><?= number_format($table['size_mb'], 2) ?></td>
                                        <td><span class="badge bg-secondary"><?= esc($table['engine']) ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="optimizeTable('<?= esc($table['table_name']) ?>')">
                                                <i class="bi bi-gear"></i> Optimize
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Connections -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Active Processes</h5>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <?php foreach ($status['connections']['processes'] as $process): ?>
                        <?php if ($process['Command'] !== 'Sleep'): ?>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">ID: <?= $process['Id'] ?></small>
                                    <small class="badge bg-<?= $process['Command'] === 'Query' ? 'success' : 'secondary' ?>">
                                        <?= esc($process['Command']) ?>
                                    </small>
                                </div>
                                <small class="text-muted d-block">User: <?= esc($process['User']) ?></small>
                                <small class="text-muted d-block">Time: <?= $process['Time'] ?>s</small>
                                <?php if (!empty($process['Info'])): ?>
                                    <small><code><?= esc(substr($process['Info'], 0, 50)) ?>...</code></small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function refreshStatus() {
        location.reload();
    }

    function optimizeTable(tableName) {
        if (!confirm(`Optimize table: ${tableName}?`)) return;

        $.post('<?= base_url('admin/database/optimize-table') ?>', {
            table: tableName,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        });
    }

    function optimizeAllTables() {
        if (!confirm('Optimize all tables? This may take a while.')) return;

        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Optimizing...';

        $.post('<?= base_url('admin/database/optimize-all') ?>', {
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function(response) {
            alert(response.message);
            location.reload();
        }).fail(function() {
            alert('Optimization failed');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-gear me-1"></i>Optimize All';
        });
    }
</script>
<?= $this->endSection() ?>