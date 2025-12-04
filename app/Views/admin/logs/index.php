<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-file-text me-2"></i>Application Logs</h1>
            <p class="text-muted mb-0">View and manage application error logs</p>
        </div>
        <div>
            <button class="btn btn-outline-danger btn-sm" onclick="clearOldLogs()">
                <i class="bi bi-trash me-1"></i>Clear Old Logs
            </button>
            <button class="btn btn-outline-primary btn-sm" onclick="downloadLog()">
                <i class="bi bi-download me-1"></i>Download
            </button>
            <button class="btn btn-primary btn-sm" onclick="refreshLogs()">
                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Log Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Errors</h6>
                            <h3 class="mb-0 text-danger" id="stat-errors">0</h3>
                        </div>
                        <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Warnings</h6>
                            <h3 class="mb-0 text-warning" id="stat-warnings">0</h3>
                        </div>
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Info</h6>
                            <h3 class="mb-0 text-info" id="stat-info">0</h3>
                        </div>
                        <i class="bi bi-info-circle text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Total Lines</h6>
                            <h3 class="mb-0" id="stat-total">0</h3>
                        </div>
                        <i class="bi bi-list-ul text-secondary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Log Level</label>
                    <select class="form-select" id="log-level" onchange="refreshLogs()">
                        <option value="all">All Levels</option>
                        <option value="error">Error</option>
                        <option value="warning">Warning</option>
                        <option value="info">Info</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Lines to Show</label>
                    <select class="form-select" id="log-lines" onchange="refreshLogs()">
                        <option value="50">Last 50 lines</option>
                        <option value="100" selected>Last 100 lines</option>
                        <option value="200">Last 200 lines</option>
                        <option value="500">Last 500 lines</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="log-search" placeholder="Search in logs..." onkeyup="filterLogs()">
                </div>
            </div>
        </div>
    </div>

    <!-- Log Viewer -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-terminal me-2"></i>Log Output</h5>
        </div>
        <div class="card-body p-0">
            <div id="log-viewer" style="background: #1e293b; color: #e2e8f0; font-family: 'Courier New', monospace; font-size: 0.85rem; padding: 1rem; max-height: 600px; overflow-y: auto;">
                <div class="text-center text-muted py-5">
                    <i class="bi bi-hourglass-split" style="font-size: 3rem;"></i>
                    <p class="mt-2">Loading logs...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        refreshLogs();
        loadStats();

        // Auto-refresh every 10 seconds
        setInterval(refreshLogs, 10000);
        setInterval(loadStats, 10000);
    });

    function refreshLogs() {
        const level = $('#log-level').val();
        const lines = $('#log-lines').val();

        $.get('<?= base_url('admin/logs/application') ?>', {
            level: level,
            lines: lines
        }, function(response) {
            if (response.success && response.data.logs) {
                displayLogs(response.data.logs);
            }
        });
    }

    function loadStats() {
        $.get('<?= base_url('admin/logs/stats') ?>', function(response) {
            if (response.success && response.data) {
                $('#stat-errors').text(response.data.errors);
                $('#stat-warnings').text(response.data.warnings);
                $('#stat-info').text(response.data.info);
                $('#stat-total').text(response.data.total);
            }
        });
    }

    function displayLogs(logs) {
        let html = '';

        if (logs.length === 0) {
            html = '<div class="text-center text-muted py-5"><i class="bi bi-inbox" style="font-size: 3rem;"></i><p class="mt-2">No logs found</p></div>';
        } else {
            logs.forEach(line => {
                let className = '';
                let icon = '';

                if (line.includes('ERROR')) {
                    className = 'text-danger';
                    icon = '❌';
                } else if (line.includes('WARNING')) {
                    className = 'text-warning';
                    icon = '⚠️';
                } else if (line.includes('INFO')) {
                    className = 'text-info';
                    icon = 'ℹ️';
                } else if (line.includes('CRITICAL')) {
                    className = 'text-danger fw-bold';
                    icon = '🔥';
                } else {
                    className = 'text-light';
                }

                html += `<div class="${className} log-line" style="padding: 2px 0; line-height: 1.5;">${icon} ${escapeHtml(line)}</div>`;
            });
        }

        $('#log-viewer').html(html);

        // Apply search filter if exists
        filterLogs();
    }

    function filterLogs() {
        const searchTerm = $('#log-search').val().toLowerCase();

        $('.log-line').each(function() {
            const text = $(this).text().toLowerCase();
            if (text.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function downloadLog() {
        window.location.href = '<?= base_url('admin/logs/download') ?>?date=' + new Date().toISOString().split('T')[0];
    }

    function clearOldLogs() {
        const days = prompt('Delete logs older than how many days?', '30');

        if (!days || days < 1) return;

        if (!confirm(`Delete all logs older than ${days} days?`)) return;

        $.post('<?= base_url('admin/logs/clear-old') ?>', {
            days: days,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function(response) {
            alert(response.message);
            refreshLogs();
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
<?= $this->endSection() ?>