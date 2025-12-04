<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Modern Metric Cards */
    .metric-card {
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .metric-value {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
    }

    .metric-label {
        font-size: 0.85rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .metric-sub {
        font-size: 0.8rem;
        opacity: 0.8;
        margin-top: 0.5rem;
    }

    /* Gradient backgrounds */
    .bg-gradient-cpu {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-ram {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .bg-gradient-disk {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .bg-gradient-uptime {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    /* Status indicators */
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }

    .status-dot.running {
        background: #10b981;
    }

    .status-dot.stopped {
        background: #ef4444;
    }

    .status-dot.warning {
        background: #f59e0b;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            box-shadow: 0 0 0 0 currentColor;
        }

        50% {
            opacity: 0.7;
            box-shadow: 0 0 0 4px transparent;
        }
    }

    /* Section headers */
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .section-header i {
        font-size: 1.25rem;
        margin-right: 0.75rem;
        color: #6366f1;
    }

    .section-header h5 {
        margin: 0;
        font-weight: 600;
        color: #1f2937;
    }

    /* Log viewer */
    .log-viewer {
        background: #1a1a2e;
        border-radius: 12px;
        padding: 1rem;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        font-size: 0.8rem;
        max-height: 300px;
        overflow-y: auto;
    }

    .log-line {
        padding: 4px 8px;
        border-radius: 4px;
        margin-bottom: 2px;
        line-height: 1.5;
    }

    .log-line:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .log-error {
        color: #f87171;
    }

    .log-warning {
        color: #fbbf24;
    }

    .log-info {
        color: #60a5fa;
    }

    .log-debug {
        color: #9ca3af;
    }

    /* Table improvements */
    .table-modern {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .table-modern thead {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
    }

    .table-modern thead th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-modern tbody tr {
        transition: background 0.2s;
    }

    .table-modern tbody tr:hover {
        background: #f8fafc;
    }

    .table-modern tbody td {
        padding: 0.875rem 1rem;
        vertical-align: middle;
        border-color: #f1f5f9;
    }

    /* Info cards */
    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.3s;
    }

    .info-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    /* Badge styles */
    .badge-success-soft {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
    }

    .badge-danger-soft {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
    }

    .badge-warning-soft {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
    }

    /* Auto-refresh indicator */
    .refresh-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(99, 102, 241, 0.1);
        border-radius: 20px;
        font-size: 0.85rem;
        color: #6366f1;
    }

    .refresh-indicator .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid currentColor;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">
                <i class="bi bi-speedometer2 text-primary me-2"></i>System Monitoring
            </h1>
            <p class="text-muted mb-0">Real-time server performance and health status</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="refresh-indicator">
                <div class="spinner"></div>
                <span>Auto-refresh: 10s</span>
            </div>
            <button class="btn btn-primary" onclick="refreshAll()">
                <i class="bi bi-arrow-clockwise me-1"></i>Refresh Now
            </button>
        </div>
    </div>

    <!-- System Metrics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="metric-card bg-gradient-cpu">
                <div class="metric-label"><i class="bi bi-cpu me-1"></i>CPU Usage</div>
                <div class="metric-value" id="cpu-value"><?= number_format($metrics['cpu']['usage_percent'], 1) ?>%</div>
                <div class="progress mt-3" style="height: 6px; background: rgba(255,255,255,0.3);">
                    <div class="progress-bar bg-white" id="cpu-bar" style="width: <?= $metrics['cpu']['usage_percent'] ?>%"></div>
                </div>
                <div class="metric-sub">Load: <?= number_format($metrics['cpu']['load_1min'], 2) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card bg-gradient-ram">
                <div class="metric-label"><i class="bi bi-memory me-1"></i>RAM Usage</div>
                <div class="metric-value" id="ram-value"><?= number_format($metrics['ram']['usage_percent'], 1) ?>%</div>
                <div class="progress mt-3" style="height: 6px; background: rgba(255,255,255,0.3);">
                    <div class="progress-bar bg-white" id="ram-bar" style="width: <?= $metrics['ram']['usage_percent'] ?>%"></div>
                </div>
                <div class="metric-sub"><?= number_format($metrics['ram']['used_mb'], 0) ?> / <?= number_format($metrics['ram']['total_mb'], 0) ?> MB</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card bg-gradient-disk">
                <div class="metric-label"><i class="bi bi-hdd me-1"></i>Disk Usage</div>
                <div class="metric-value" id="disk-value"><?= number_format($metrics['disk']['usage_percent'], 1) ?>%</div>
                <div class="progress mt-3" style="height: 6px; background: rgba(255,255,255,0.3);">
                    <div class="progress-bar bg-white" id="disk-bar" style="width: <?= $metrics['disk']['usage_percent'] ?>%"></div>
                </div>
                <div class="metric-sub"><?= number_format($metrics['disk']['free_gb'], 1) ?> GB free</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card bg-gradient-uptime">
                <div class="metric-label"><i class="bi bi-clock-history me-1"></i>Uptime</div>
                <div class="metric-value"><?= $metrics['uptime']['days'] ?>d</div>
                <div class="metric-sub mt-3"><?= $metrics['uptime']['formatted'] ?></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Docker Containers -->
            <div class="info-card p-4 mb-4">
                <div class="section-header">
                    <i class="bi bi-box"></i>
                    <h5>Docker Containers</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Health</th>
                                <th>Logs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($containers as $container): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($container['service']) ?></strong>
                                        <small class="d-block text-muted"><?= esc($container['name']) ?></small>
                                    </td>
                                    <td>
                                        <span class="status-dot <?= $container['status'] === 'running' ? 'running' : 'stopped' ?>"></span>
                                        <span class="badge <?= $container['status'] === 'running' ? 'badge-success-soft' : 'badge-danger-soft' ?> ms-2">
                                            <?= ucfirst($container['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($container['health'] === 'healthy'): ?>
                                            <span class="text-success"><i class="bi bi-check-circle-fill"></i> Healthy</span>
                                        <?php elseif ($container['health'] === 'none'): ?>
                                            <span class="text-muted">-</span>
                                        <?php else: ?>
                                            <span class="text-warning"><i class="bi bi-exclamation-triangle-fill"></i> <?= ucfirst($container['health']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewLogs('<?= esc($container['service']) ?>')">
                                            <i class="bi bi-terminal"></i> View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Database Tables -->
            <div class="info-card p-4 mb-4">
                <div class="section-header">
                    <i class="bi bi-database"></i>
                    <h5>Database Tables</h5>
                    <span class="badge bg-primary ms-auto"><?= count($tables) ?> tables</span>
                </div>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead class="sticky-top bg-light">
                            <tr>
                                <th>Table</th>
                                <th class="text-end">Rows</th>
                                <th class="text-end">Size</th>
                                <th>Engine</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tables as $table): ?>
                                <tr>
                                    <td><code class="text-primary"><?= esc($table['table_name']) ?></code></td>
                                    <td class="text-end"><?= number_format($table['table_rows'] ?? 0) ?></td>
                                    <td class="text-end"><?= number_format($table['size_mb'], 2) ?> MB</td>
                                    <td><span class="badge bg-secondary"><?= esc($table['engine']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Application Logs -->
            <div class="info-card p-4">
                <div class="section-header">
                    <i class="bi bi-file-text"></i>
                    <h5>Application Logs</h5>
                    <div class="ms-auto d-flex gap-2">
                        <select class="form-select form-select-sm" id="log-level" onchange="loadLogs()" style="width: auto;">
                            <option value="all">All Levels</option>
                            <option value="error">Errors Only</option>
                            <option value="warning">Warnings</option>
                            <option value="info">Info</option>
                        </select>
                    </div>
                </div>
                <div class="log-viewer" id="log-container">
                    <?php if (empty($recentLogs)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No logs for today</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentLogs as $line): ?>
                            <div class="log-line <?php
                                                    if (stripos($line, 'ERROR') !== false || stripos($line, 'CRITICAL') !== false) echo 'log-error';
                                                    elseif (stripos($line, 'WARNING') !== false) echo 'log-warning';
                                                    elseif (stripos($line, 'INFO') !== false) echo 'log-info';
                                                    else echo 'log-debug';
                                                    ?>"><?= esc($line) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Database Status -->
            <div class="info-card p-4 mb-4">
                <div class="section-header">
                    <i class="bi bi-database-gear"></i>
                    <h5>Database Status</h5>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <div class="h4 mb-1 text-primary"><?= number_format($dbStatus['database']['size_mb'], 2) ?></div>
                            <small class="text-muted">Size (MB)</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <div class="h4 mb-1 text-success"><?= $dbStatus['connections']['active'] ?></div>
                            <small class="text-muted">Connections</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <div class="h4 mb-1 text-info"><?= count($tables) ?></div>
                            <small class="text-muted">Tables</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <div class="h4 mb-1 text-warning"><?= $dbStatus['uptime']['days'] ?>d</div>
                            <small class="text-muted">DB Uptime</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Statistics -->
            <div class="info-card p-4 mb-4">
                <div class="section-header">
                    <i class="bi bi-graph-up"></i>
                    <h5>Today's Log Stats</h5>
                </div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-x-circle text-danger me-2"></i>Errors</span>
                        <span class="badge badge-danger-soft px-3 py-2"><?= $logStats['errors'] ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-exclamation-triangle text-warning me-2"></i>Warnings</span>
                        <span class="badge badge-warning-soft px-3 py-2"><?= $logStats['warnings'] ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-info-circle text-info me-2"></i>Info</span>
                        <span class="badge bg-info bg-opacity-15 text-info px-3 py-2"><?= $logStats['info'] ?></span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Entries</span>
                        <span class="badge bg-secondary px-3 py-2"><?= $logStats['total'] ?></span>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="info-card p-4">
                <div class="section-header">
                    <i class="bi bi-info-circle"></i>
                    <h5>System Info</h5>
                </div>
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">CPU Cores</td>
                        <td class="text-end fw-bold"><?= $metrics['cpu']['cpu_count'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total RAM</td>
                        <td class="text-end fw-bold"><?= number_format($metrics['ram']['total_mb'], 0) ?> MB</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Disk</td>
                        <td class="text-end fw-bold"><?= number_format($metrics['disk']['total_gb'], 1) ?> GB</td>
                    </tr>
                    <tr>
                        <td class="text-muted">PHP Version</td>
                        <td class="text-end fw-bold"><?= PHP_VERSION ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">MySQL Version</td>
                        <td class="text-end fw-bold"><?= $dbStatus['variables']['version'] ?? 'N/A' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Logs Modal -->
<div class="modal fade" id="logsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-terminal me-2"></i>Container Logs: <span id="modal-service"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="log-viewer" id="modal-logs" style="max-height: 500px; border-radius: 0;">
                    Loading...
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto-refresh every 10 seconds
    let refreshInterval = setInterval(refreshMetrics, 10000);

    function refreshAll() {
        refreshMetrics();
        loadLogs();
    }

    function refreshMetrics() {
        $.get('<?= base_url('admin/monitoring/metrics') ?>', function(response) {
            if (response.success && response.data) {
                const sys = response.data.system;

                // Update CPU
                $('#cpu-value').text(sys.cpu.usage_percent.toFixed(1) + '%');
                $('#cpu-bar').css('width', sys.cpu.usage_percent + '%');

                // Update RAM
                $('#ram-value').text(sys.ram.usage_percent.toFixed(1) + '%');
                $('#ram-bar').css('width', sys.ram.usage_percent + '%');

                // Update Disk
                $('#disk-value').text(sys.disk.usage_percent.toFixed(1) + '%');
                $('#disk-bar').css('width', sys.disk.usage_percent + '%');
            }
        });
    }

    function loadLogs() {
        const level = $('#log-level').val();

        $.get('<?= base_url('admin/monitoring/logs') ?>', {
            level: level,
            lines: 50
        }, function(response) {
            if (response.success && response.data.logs) {
                let html = '';
                response.data.logs.forEach(line => {
                    let cls = 'log-debug';
                    if (line.includes('ERROR') || line.includes('CRITICAL')) cls = 'log-error';
                    else if (line.includes('WARNING')) cls = 'log-warning';
                    else if (line.includes('INFO')) cls = 'log-info';

                    html += `<div class="log-line ${cls}">${escapeHtml(line)}</div>`;
                });

                if (html === '') {
                    html = '<div class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2 mb-0">No logs found</p></div>';
                }

                $('#log-container').html(html);
            }
        });
    }

    function viewLogs(service) {
        $('#modal-service').text(service);
        $('#modal-logs').html('<div class="text-center py-4"><div class="spinner-border text-light"></div></div>');
        $('#logsModal').modal('show');

        $.get('<?= base_url('admin/monitoring/container-logs') ?>', {
            service: service,
            lines: 100
        }, function(response) {
            if (response.success && response.data.logs) {
                const lines = response.data.logs.split('\n');
                let html = '';
                lines.forEach(line => {
                    if (line.trim()) {
                        let cls = 'log-debug';
                        if (line.includes('error') || line.includes('ERROR')) cls = 'log-error';
                        else if (line.includes('warn') || line.includes('WARN')) cls = 'log-warning';
                        html += `<div class="log-line ${cls}">${escapeHtml(line)}</div>`;
                    }
                });
                $('#modal-logs').html(html || '<div class="text-center text-muted py-4">No logs available</div>');
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