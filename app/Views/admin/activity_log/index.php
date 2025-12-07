<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-activity me-2"></i><?= lang('App.activityLog') ?? 'Activity Log' ?>
            </h1>
            <p class="text-muted mb-0"><?= lang('App.activityLogDescription') ?? 'ติดตามการกระทำของผู้ใช้ทั้งหมดในระบบ' ?></p>
        </div>
        <a href="<?= base_url('settings') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50"><?= lang('App.totalToday') ?? 'กิจกรรมวันนี้' ?></h6>
                            <h2 class="mb-0"><?= number_format($stats['total_today']) ?></h2>
                        </div>
                        <i class="bi bi-activity fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50"><?= lang('App.createActions') ?? 'สร้างใหม่' ?></h6>
                            <h2 class="mb-0"><?= number_format($stats['creates_today']) ?></h2>
                        </div>
                        <i class="bi bi-plus-circle fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-dark-50"><?= lang('App.updateActions') ?? 'แก้ไข' ?></h6>
                            <h2 class="mb-0"><?= number_format($stats['updates_today']) ?></h2>
                        </div>
                        <i class="bi bi-pencil fs-1 text-dark-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50"><?= lang('App.deleteActions') ?? 'ลบ' ?></h6>
                            <h2 class="mb-0"><?= number_format($stats['deletes_today']) ?></h2>
                        </div>
                        <i class="bi bi-trash fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-funnel me-2"></i><?= lang('App.filter') ?>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label"><?= lang('App.user') ?></label>
                    <select class="form-select" name="user_id">
                        <option value=""><?= lang('App.all') ?></option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= $filters['user_id'] == $user['id'] ? 'selected' : '' ?>>
                                <?= esc($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><?= lang('App.actions') ?></label>
                    <select class="form-select" name="action">
                        <option value=""><?= lang('App.all') ?></option>
                        <option value="CREATE" <?= $filters['action'] === 'CREATE' ? 'selected' : '' ?>>CREATE</option>
                        <option value="UPDATE" <?= $filters['action'] === 'UPDATE' ? 'selected' : '' ?>>UPDATE</option>
                        <option value="DELETE" <?= $filters['action'] === 'DELETE' ? 'selected' : '' ?>>DELETE</option>
                        <option value="LOGIN" <?= $filters['action'] === 'LOGIN' ? 'selected' : '' ?>>LOGIN</option>
                        <option value="LOGOUT" <?= $filters['action'] === 'LOGOUT' ? 'selected' : '' ?>>LOGOUT</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><?= lang('App.type') ?></label>
                    <select class="form-select" name="table">
                        <option value=""><?= lang('App.all') ?></option>
                        <?php foreach ($tables as $table): ?>
                            <option value="<?= $table['table_name'] ?>" <?= $filters['table'] === $table['table_name'] ? 'selected' : '' ?>>
                                <?= ucfirst(str_replace('_', ' ', $table['table_name'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><?= lang('App.fromDate') ?></label>
                    <input type="date" class="form-control" name="from" value="<?= $filters['from'] ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label"><?= lang('App.toDate') ?></label>
                    <input type="date" class="form-control" name="to" value="<?= $filters['to'] ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i><?= lang('App.search') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Log Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>
                <i class="bi bi-table me-2"></i><?= lang('App.activityLog') ?? 'Activity Log' ?>
            </span>
            <span class="badge bg-secondary"><?= count($logs) ?> <?= lang('App.records') ?? 'รายการ' ?></span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($logs)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox d-block fs-1 mb-2 opacity-50"></i>
                    <p class="mb-0"><?= lang('App.noRecords') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 160px;"><?= lang('App.date') ?></th>
                                <th style="width: 130px;"><?= lang('App.user') ?></th>
                                <th style="width: 100px;"><?= lang('App.actions') ?></th>
                                <th style="width: 130px;"><?= lang('App.type') ?></th>
                                <th>ID</th>
                                <th><?= lang('App.description') ?></th>
                                <th style="width: 130px;"><?= lang('App.ipAddress') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td>
                                        <div class="small">
                                            <div><?= date('d/m/Y', strtotime($log['created_at'])) ?></div>
                                            <div class="text-muted"><?= date('H:i:s', strtotime($log['created_at'])) ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($log['user_name']): ?>
                                            <div class="fw-medium"><?= esc($log['user_name']) ?></div>
                                            <small class="text-muted">@<?= esc($log['username']) ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">System</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $actionClass = match ($log['action']) {
                                            'CREATE' => 'bg-success',
                                            'UPDATE' => 'bg-warning text-dark',
                                            'DELETE' => 'bg-danger',
                                            'LOGIN' => 'bg-info',
                                            'LOGOUT' => 'bg-secondary',
                                            default => 'bg-secondary'
                                        };
                                        $actionIcon = match ($log['action']) {
                                            'CREATE' => 'bi-plus-circle',
                                            'UPDATE' => 'bi-pencil',
                                            'DELETE' => 'bi-trash',
                                            'LOGIN' => 'bi-box-arrow-in-right',
                                            'LOGOUT' => 'bi-box-arrow-left',
                                            default => 'bi-activity'
                                        };
                                        ?>
                                        <span class="badge <?= $actionClass ?>">
                                            <i class="bi <?= $actionIcon ?> me-1"></i>
                                            <?= $log['action'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <?= ucfirst(str_replace('_', ' ', $log['table_name'] ?? '-')) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($log['record_id']): ?>
                                            <code>#<?= $log['record_id'] ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($log['new_values']): ?>
                                            <?php
                                            $newValues = json_decode($log['new_values'], true);
                                            if ($newValues) {
                                                $keys = array_keys($newValues);
                                                $preview = array_slice($keys, 0, 3);
                                                echo '<span class="small text-muted">' . implode(', ', $preview);
                                                if (count($keys) > 3) {
                                                    echo ' +' . (count($keys) - 3) . ' more';
                                                }
                                                echo '</span>';
                                            }
                                            ?>
                                        <?php elseif ($log['old_values']): ?>
                                            <span class="small text-danger">
                                                <i class="bi bi-trash me-1"></i>Deleted record
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>

                                        <!-- View Details Button -->
                                        <button type="button" class="btn btn-sm btn-link p-0 ms-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailsModal"
                                            onclick="showDetails(<?= htmlspecialchars(json_encode($log)) ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <code class="small"><?= esc($log['ip_address'] ?? '-') ?></code>
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

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i><?= lang('App.details') ?? 'รายละเอียด' ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small"><?= lang('App.user') ?></label>
                        <div id="modalUser" class="fw-medium"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small"><?= lang('App.actions') ?></label>
                        <div id="modalAction"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small"><?= lang('App.date') ?></label>
                        <div id="modalDate"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small"><?= lang('App.type') ?></label>
                        <div id="modalTable"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Record ID</label>
                        <div id="modalRecordId"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small"><?= lang('App.ipAddress') ?></label>
                        <div id="modalIp"></div>
                    </div>
                </div>

                <!-- Old Values -->
                <div id="oldValuesSection" class="mb-3" style="display: none;">
                    <label class="form-label text-muted small">
                        <i class="bi bi-arrow-left-circle text-danger me-1"></i>
                        <?= lang('App.oldValues') ?? 'ค่าเดิม' ?>
                    </label>
                    <pre id="modalOldValues" class="bg-light p-3 rounded small mb-0" style="max-height: 200px; overflow-y: auto;"></pre>
                </div>

                <!-- New Values -->
                <div id="newValuesSection" class="mb-3" style="display: none;">
                    <label class="form-label text-muted small">
                        <i class="bi bi-arrow-right-circle text-success me-1"></i>
                        <?= lang('App.newValues') ?? 'ค่าใหม่' ?>
                    </label>
                    <pre id="modalNewValues" class="bg-light p-3 rounded small mb-0" style="max-height: 200px; overflow-y: auto;"></pre>
                </div>

                <!-- User Agent -->
                <div>
                    <label class="form-label text-muted small">User Agent</label>
                    <div id="modalUserAgent" class="small text-muted"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?= lang('App.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function showDetails(log) {
        document.getElementById('modalUser').textContent = log.user_name || 'System';
        document.getElementById('modalAction').innerHTML = '<span class="badge bg-' + getActionColor(log.action) + '">' + log.action + '</span>';
        document.getElementById('modalDate').textContent = log.created_at;
        document.getElementById('modalTable').innerHTML = '<span class="badge bg-light text-dark">' + (log.table_name || '-') + '</span>';
        document.getElementById('modalRecordId').innerHTML = log.record_id ? '<code>#' + log.record_id + '</code>' : '-';
        document.getElementById('modalIp').innerHTML = '<code>' + (log.ip_address || '-') + '</code>';
        document.getElementById('modalUserAgent').textContent = log.user_agent || '-';

        // Old Values
        const oldSection = document.getElementById('oldValuesSection');
        const oldPre = document.getElementById('modalOldValues');
        if (log.old_values) {
            oldSection.style.display = 'block';
            try {
                const parsed = JSON.parse(log.old_values);
                oldPre.textContent = JSON.stringify(parsed, null, 2);
            } catch (e) {
                oldPre.textContent = log.old_values;
            }
        } else {
            oldSection.style.display = 'none';
        }

        // New Values
        const newSection = document.getElementById('newValuesSection');
        const newPre = document.getElementById('modalNewValues');
        if (log.new_values) {
            newSection.style.display = 'block';
            try {
                const parsed = JSON.parse(log.new_values);
                newPre.textContent = JSON.stringify(parsed, null, 2);
            } catch (e) {
                newPre.textContent = log.new_values;
            }
        } else {
            newSection.style.display = 'none';
        }
    }

    function getActionColor(action) {
        switch (action) {
            case 'CREATE':
                return 'success';
            case 'UPDATE':
                return 'warning text-dark';
            case 'DELETE':
                return 'danger';
            case 'LOGIN':
                return 'info';
            case 'LOGOUT':
                return 'secondary';
            default:
                return 'secondary';
        }
    }
</script>
<?= $this->endSection() ?>