<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-rocket me-2"></i>Enterprise Dashboard</h1>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Completed Features</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-speedometer2 me-2"></i>System Monitor</span>
                            <span class="badge bg-success">✅ Active</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-database-gear me-2"></i>Database Manager</span>
                            <span class="badge bg-success">✅ Active</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-file-text me-2"></i>Log Viewer</span>
                            <span class="badge bg-success">✅ Active</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-box me-2"></i>Docker Management</span>
                            <span class="badge bg-success">✅ Active</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-tools me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('admin/system') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-speedometer2 me-2"></i>System Monitor
                        </a>
                        <a href="<?= base_url('admin/database') ?>" class="btn btn-outline-success">
                            <i class="bi bi-database-gear me-2"></i>Database Manager
                        </a>
                        <a href="<?= base_url('admin/logs') ?>" class="btn btn-outline-warning">
                            <i class="bi bi-file-text me-2"></i>Application Logs
                        </a>
                        <a href="<?= base_url('settings/backup') ?>" class="btn btn-outline-info">
                            <i class="bi bi-cloud-download me-2"></i>Backup & Restore
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enterprise Features Summary -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Enterprise Grade Features (60% Complete)</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-success"><i class="bi bi-check-circle me-2"></i>Implemented</h6>
                    <ul>
                        <li>✅ Real-time System Monitoring (CPU, RAM, Disk, Uptime)</li>
                        <li>✅ Database Management (Tables, Optimization, Connections)</li>
                        <li>✅ Docker Container Management</li>
                        <li>✅ Application Log Viewer with Statistics</li>
                        <li>✅ Quick Cache Management</li>
                        <li>✅ Backup & Restore System</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="bi bi-clock me-2"></i>Future Enhancements</h6>
                    <ul class="text-muted">
                        <li>📊 Historical Performance Graphs</li>
                        <li>🔔 Alert System with Email Notifications</li>
                        <li>🚀 One-Click Deployment Tools</li>
                        <li>🔒 Security Dashboard</li>
                        <li>⚡ Performance Profiler</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>