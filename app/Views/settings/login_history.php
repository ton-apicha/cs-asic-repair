<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title" style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary);">
            <i class="bi bi-clock-history text-primary me-2"></i>
            ประวัติการเข้าสู่ระบบ
        </h1>
        <p class="text-muted mb-0">รายการเข้าสู่ระบบล่าสุด 20 รายการ</p>
    </div>
    <a href="<?= base_url('settings') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        กลับ
    </a>
</div>

<!-- Login History Card -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($history)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">ไม่พบประวัติการเข้าสู่ระบบ</p>
            </div>
        <?php else: ?>
            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 180px;">วันที่/เวลา</th>
                            <th style="width: 120px;">สถานะ</th>
                            <th>อุปกรณ์</th>
                            <th>เบราว์เซอร์</th>
                            <th style="width: 140px;">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $log): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 text-muted me-2"></i>
                                        <div>
                                            <div><?= date('d/m/Y', strtotime($log['login_at'])) ?></div>
                                            <small class="text-muted"><?= date('H:i:s', strtotime($log['login_at'])) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($log['status'] === 'success'): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            สำเร็จ
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            ล้มเหลว
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $deviceIcon = match ($log['device_type'] ?? '') {
                                        'Mobile' => 'bi-phone',
                                        'Tablet' => 'bi-tablet',
                                        default => 'bi-pc-display'
                                    };
                                    ?>
                                    <i class="bi <?= $deviceIcon ?> text-primary me-2"></i>
                                    <span><?= esc($log['device_type'] ?? 'Unknown') ?></span>
                                    <small class="text-muted d-block"><?= esc($log['platform'] ?? '') ?></small>
                                </td>
                                <td>
                                    <i class="bi bi-globe text-secondary me-2"></i>
                                    <?= esc($log['browser'] ?? 'Unknown') ?>
                                </td>
                                <td>
                                    <code class="small"><?= esc($log['ip_address']) ?></code>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="d-md-none">
                <?php foreach ($history as $log): ?>
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-medium">
                                    <?= date('d/m/Y H:i', strtotime($log['login_at'])) ?>
                                </div>
                                <?php if ($log['status'] === 'success'): ?>
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i>สำเร็จ
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-x-circle me-1"></i>ล้มเหลว
                                    </span>
                                <?php endif; ?>
                            </div>
                            <code class="small text-muted"><?= esc($log['ip_address']) ?></code>
                        </div>
                        <div class="small text-muted">
                            <?php
                            $deviceIcon = match ($log['device_type'] ?? '') {
                                'Mobile' => 'bi-phone',
                                'Tablet' => 'bi-tablet',
                                default => 'bi-pc-display'
                            };
                            ?>
                            <i class="bi <?= $deviceIcon ?> me-1"></i>
                            <?= esc($log['device_type'] ?? 'Unknown') ?>
                            •
                            <?= esc($log['browser'] ?? 'Unknown') ?>
                            <?php if (!empty($log['platform'])): ?>
                                • <?= esc($log['platform']) ?>
                            <?php endif; ?>
                        </div>
                        <?php if ($log['status'] === 'failed' && !empty($log['failure_reason'])): ?>
                            <div class="small text-danger mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                <?= esc($log['failure_reason']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Info Box -->
<div class="alert alert-info mt-4">
    <i class="bi bi-info-circle me-2"></i>
    <strong>หมายเหตุ:</strong> ประวัติการเข้าสู่ระบบจะถูกเก็บไว้ 90 วัน เพื่อความปลอดภัยของบัญชี
</div>

<?= $this->endSection() ?>