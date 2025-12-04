<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-5px);
    }

    .metric-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .metric-label {
        font-size: 0.9rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .progress-ring {
        width: 120px;
        height: 120px;
    }

    .container-card {
        border-left: 4px solid #3b82f6;
        transition: all 0.3s ease;
    }

    .container-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .log-viewer {
        background: #1e293b;
        color: #e2e8f0;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        padding: 1rem;
        border-radius: 8px;
        max-height: 400px;
        overflow-y: auto;
    }

    .log-line {
        padding: 2px 0;
    }

    .log-error {
        color: #ef4444;
    }

    .log-warning {
        color: #f59e0b;
    }

    .log-info {
        color: #3b82f6;
    }

    .log-success {
        color: #10b981;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-speedometer2 me-2"></i>System Monitoring</h1>
            <p class="text-muted mb-0">Real-time server performance and status</p>
        </div>
        <div>
            <button class="btn btn-outline-primary" onclick="refreshMetrics()">
                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
            </button>
            <span class="badge bg-success ms-2">
                <i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i>
                Live
            </span>
        </div>
    </div>

    <!-- System Metrics Cards -->
    <div class="row g-4 mb-4">
        <!-- CPU Card -->
        <div class="col-md-3">
            <div class="metric-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="metric-label">
                    <i class="bi bi-cpu me-1"></i>CPU Usage
                </div>
                <div class="metric-value" id="cpu-usage">
                    <?= number_format($metrics['cpu']['usage_percent'], 1) ?>%
                </div>
                <div class="progress" style="height: 6px; background: rgba(255,255,255,0.3);">
                    <div class="progress-bar" role="progressbar"
                        style="width: <?= $metrics['cpu']['usage_percent'] ?>%; background: white;"
                        id="cpu-progress"></div>
                </div>
                <small class="mt-2 d-block">Load: <?= number_format($metrics['cpu']['load_1min'], 2) ?></small>
            </div>
        </div>

        <!-- RAM Card -->
        <div class="col-md-3">
            <div class="metric-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="metric-label">
                    <i class="bi bi-memory me-1"></i>RAM Usage
                </div>
                <div class="metric-value" id="ram-usage">
                    <?= number_format($metrics['ram']['usage_percent'], 1) ?>%
                </div>
                <div class="progress" style="height: 6px; background: rgba(255,255,255,0.3);">
                    <div class="progress-bar" role="progressbar"
                        style="width: <?= $metrics['ram']['usage_percent'] ?>%; background: white;"
                        id="ram-progress"></div>
                </div>
                <small class="mt-2 d-block">
                    <?= number_format($metrics['ram']['used_mb'], 0) ?> /
                    <?= number_format($metrics['ram']['total_mb'], 0) ?> MB
                </small>
            </div>
        </div>

        <!-- Disk Card -->
        <div class="col-md-3">
            <div class="metric-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="metric-label">
                    <i class="bi bi-hdd me-1"></i>Disk Usage
                </div>
                <div class="metric-value" id="disk-usage">
                    <?= number_format($metrics['disk']['usage_percent'], 1) ?>%
                </div>
                <div class="progress" style="height: 6px; background: rgba(255,255,255,0.3);">
                    <div class="progress-bar" role="progressbar"
                        style="width: <?= $metrics['disk']['usage_percent'] ?>%; background: white;"
                        id="disk-progress"></div>
                </div>
                <small class="mt-2 d-block">
                    <?= number_format($metrics['disk']['free_gb'], 1) ?> GB free
                </small>
            </div>
        </div>

        <!-- Uptime Card -->
        <div class="col-md-3">
            <div class="metric-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="metric-label">
                    <i class="bi bi-clock me-1"></i>Uptime
                </div>
                <div class="metric-value" style="font-size: 1.8rem;" id="uptime">
                    <?= $metrics['uptime']['days'] ?>d
                </div>
                <small class="mt-2 d-block"><?= $metrics['uptime']['formatted'] ?></small>
            </div>
        </div>
    </div>

    <!-- Docker Containers -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-box me-2"></i>Docker Containers</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="containers-list">
                        <?php foreach ($containers as $container): ?>
                            <div class="col-md-4">
                                <div class="card container-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h6 class="mb-0"><?= esc($container['service']) ?></h6>
                                            <span class="status-badge badge bg-<?= $container['status'] === 'running' ? 'success' : 'danger' ?>">
                                                <span class="status-dot bg-white"></span>
                                                <?= ucfirst($container['status']) ?>
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-3"><?= esc($container['name']) ?></p>
                                        <div class="btn-group btn-group-sm w-100">
                                            <button class="btn btn-outline-success" onclick="restartContainer('<?= $container['service'] ?>')">
                                                <i class="bi bi-arrow-clockwise"></i> Restart
                                            </button>
                                            <button class="btn btn-outline-primary" onclick="viewLogs('<?= $container['service'] ?>')">
                                                <i class="bi bi-file-text"></i> Logs
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="clearCache('all')">
                            <i class="bi bi-trash me-2"></i>Clear All Cache
                        </button>
                        <button class="btn btn-outline-info" onclick="clearCache('views')">
                            <i class="bi bi-eye-slash me-2"></i>Clear View Cache
                        </button>
                        <button class="btn btn-outline-warning" onclick="clearCache('session')">
                            <i class="bi bi-person-x me-2"></i>Clear Sessions
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>System Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>CPU Cores</td>
                            <td><strong><?= $metrics['cpu']['cpu_count'] ?></strong></td>
                        </tr>
                        <tr>
                            <td>Total RAM</td>
                            <td><strong><?= number_format($metrics['ram']['total_mb'], 0) ?> MB</strong></td>
                        </tr>
                        <tr>
                            <td>Total Disk</td>
                            <td><strong><?= number_format($metrics['disk']['total_gb'], 1) ?> GB</strong></td>
                        </tr>
                        <tr>
                            <td>PHP Version</td>
                            <td><strong><?= PHP_VERSION ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logs Modal -->
<div class="modal fade" id="logsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text me-2"></i>Container Logs: <span id="log-service-name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="log-viewer" id="log-content">
                    Loading logs...
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto-refresh metrics every 5 seconds
    let refreshInterval = setInterval(refreshMetrics, 5000);

    function refreshMetrics() {
        $.get('<?= base_url('admin/system/metrics') ?>', function(response) {
            if (response.success && response.data) {
                updateMetrics(response.data);
            }
        });
    }

    function updateMetrics(data) {
        // Update CPU
        $('#cpu-usage').text(data.cpu.usage_percent.toFixed(1) + '%');
        $('#cpu-progress').css('width', data.cpu.usage_percent + '%');

        // Update RAM
        $('#ram-usage').text(data.ram.usage_percent.toFixed(1) + '%');
        $('#ram-progress').css('width', data.ram.usage_percent + '%');

        // Update Disk
        $('#disk-usage').text(data.disk.usage_percent.toFixed(1) + '%');
        $('#disk-progress').css('width', data.disk.usage_percent + '%');
    }

    function restartContainer(service) {
        if (!confirm(`Are you sure you want to restart ${service} container?`)) {
            return;
        }

        $.post('<?= base_url('admin/system/restart-container') ?>', {
            service: service,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                alert(`Container ${service} restarted successfully!`);
                location.reload();
            } else {
                alert('Failed to restart container: ' + response.message);
            }
        });
    }

    function viewLogs(service) {
        $('#log-service-name').text(service);
        $('#log-content').html('Loading logs...');
        $('#logsModal').modal('show');

        $.get('<?= base_url('admin/system/container-logs') ?>', {
            service: service,
            lines: 100
        }, function(response) {
            if (response.success && response.data.logs) {
                const logs = response.data.logs.split('\n');
                let html = '';
                logs.forEach(line => {
                    let className = 'log-line';
                    if (line.includes('ERROR') || line.includes('error')) className += ' log-error';
                    else if (line.includes('WARN') || line.includes('warning')) className += ' log-warning';
                    else if (line.includes('INFO')) className += ' log-info';

                    html += `<div class="${className}">${escapeHtml(line)}</div>`;
                });
                $('#log-content').html(html);
            }
        });
    }

    function clearCache(type) {
        if (!confirm(`Clear ${type} cache?`)) {
            return;
        }

        $.post('<?= base_url('admin/system/clear-cache') ?>', {
            type: type,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert('Failed: ' + response.message);
            }
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
<?= $this->endSection() ?>