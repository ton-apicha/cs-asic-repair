<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - ASIC Repair</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #06b6d4;
            --secondary: #64748b;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
            min-height: 100vh;
        }
        
        .track-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .brand-logo {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        .brand-logo i {
            color: #3b82f6;
        }
        
        .brand-subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .search-card {
            background: #fff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            margin-bottom: 2rem;
        }
        
        .search-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .search-title i {
            color: #3b82f6;
        }
        
        .form-control-lg {
            padding: 1rem 1.25rem;
            font-size: 1.1rem;
            border-radius: 0.75rem;
            border: 2px solid #e2e8f0;
        }
        
        .form-control-lg:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-track {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            color: #fff;
            transition: all 0.3s ease;
        }
        
        .btn-track:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
        }
        
        /* Result Card */
        .result-card {
            background: #fff;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .result-header {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            padding: 1.5rem;
        }
        
        .job-id-display {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        
        .status-badge-large {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(255,255,255,0.2);
            margin-top: 0.75rem;
        }
        
        .result-body {
            padding: 1.5rem;
        }
        
        .info-section {
            margin-bottom: 1.5rem;
        }
        
        .info-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            margin-bottom: 0.75rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .info-item {
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 0.5rem;
        }
        
        .info-label {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .info-value {
            font-weight: 600;
            color: #1e293b;
            margin-top: 0.25rem;
        }
        
        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }
        
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        
        .timeline-dot {
            position: absolute;
            left: -2rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            transform: translateX(-50%);
            margin-left: 16px;
        }
        
        .timeline-dot.completed {
            background: #10b981;
            color: #fff;
        }
        
        .timeline-dot.current {
            background: #3b82f6;
            color: #fff;
            animation: pulse 2s infinite;
        }
        
        .timeline-dot.pending {
            background: #e2e8f0;
            color: #94a3b8;
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        }
        
        .timeline-content {
            padding-left: 1rem;
        }
        
        .timeline-title {
            font-weight: 600;
            color: #1e293b;
        }
        
        .timeline-title.pending {
            color: #94a3b8;
        }
        
        .timeline-desc {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        }
        
        /* Error */
        .error-card {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
        }
        
        .error-icon {
            font-size: 3rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }
        
        .error-text {
            color: #dc2626;
            font-weight: 500;
        }
        
        /* Footer */
        .portal-footer {
            text-align: center;
            padding: 2rem;
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .portal-footer a {
            color: #94a3b8;
            text-decoration: none;
        }
        
        .portal-footer a:hover {
            color: #fff;
        }
        
        /* Language Switcher */
        .lang-switcher {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
        
        .lang-switcher .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <!-- Language Switcher -->
    <div class="lang-switcher">
        <div class="btn-group btn-group-sm">
            <a href="<?= base_url('language/switch/en') ?>" class="btn btn-outline-light <?= service('request')->getLocale() === 'en' ? 'active' : '' ?>">EN</a>
            <a href="<?= base_url('language/switch/th') ?>" class="btn btn-outline-light <?= service('request')->getLocale() === 'th' ? 'active' : '' ?>">TH</a>
            <a href="<?= base_url('language/switch/zh') ?>" class="btn btn-outline-light <?= service('request')->getLocale() === 'zh' ? 'active' : '' ?>">中</a>
        </div>
    </div>

    <div class="track-container">
        <!-- Brand Header -->
        <div class="brand-header">
            <div class="brand-logo">
                <i class="bi bi-cpu-fill"></i>
                ASIC Repair
            </div>
            <div class="brand-subtitle"><?= lang('App.professionalService') ?></div>
        </div>
        
        <!-- Search Card -->
        <div class="search-card">
            <h2 class="search-title">
                <i class="bi bi-search"></i>
                <?= lang('App.trackYourStatus') ?>
            </h2>
            
            <form method="GET" action="<?= base_url('track') ?>">
                <div class="mb-4">
                    <input type="text" 
                           class="form-control form-control-lg" 
                           name="job_id" 
                           value="<?= esc($jobId ?? '') ?>"
                           placeholder="<?= lang('App.enterJobId') ?> (e.g., 2411001)"
                           required
                           autofocus>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-track">
                        <i class="bi bi-search me-2"></i><?= lang('App.trackNow') ?>
                    </button>
                </div>
            </form>
        </div>
        
        <?php if (isset($error) && $error): ?>
        <!-- Error -->
        <div class="error-card">
            <i class="bi bi-exclamation-triangle error-icon"></i>
            <p class="error-text"><?= esc($error) ?></p>
            <p class="text-muted"><?= lang('App.checkJobIdTryAgain') ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (isset($job) && $job): ?>
        <!-- Result -->
        <div class="result-card">
            <div class="result-header">
                <div class="job-id-display"><?= esc($job['job_id']) ?></div>
                <?php
                $statusText = match($job['status']) {
                    'new_checkin' => lang('App.checkedIn'),
                    'pending_repair' => lang('App.awaitingRepair'),
                    'in_progress' => lang('App.repairInProgress'),
                    'repair_done' => lang('App.repairComplete'),
                    'ready_handover' => lang('App.readyForPickup'),
                    'delivered' => lang('App.delivered'),
                    default => $job['status']
                };
                ?>
                <div class="status-badge-large"><?= $statusText ?></div>
            </div>
            
            <div class="result-body">
                <!-- Device Info -->
                <div class="info-section">
                    <div class="info-section-title"><?= lang('App.deviceInfo') ?></div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><?= lang('App.model') ?></div>
                            <div class="info-value"><?= esc($asset['brand_model'] ?? '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><?= lang('App.serialNumber') ?></div>
                            <div class="info-value"><?= esc($asset['serial_number'] ?? '-') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><?= lang('App.checkInDate') ?></div>
                            <div class="info-value"><?= date('d M Y', strtotime($job['checkin_date'])) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><?= lang('App.estimatedCost') ?></div>
                            <div class="info-value">฿<?= number_format($job['grand_total'], 0) ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Problem Description -->
                <div class="info-section">
                    <div class="info-section-title"><?= lang('App.problemDescription') ?></div>
                    <p class="mb-0" style="color: #475569;"><?= esc($job['symptom']) ?></p>
                </div>
                
                <!-- Timeline -->
                <div class="info-section">
                    <div class="info-section-title"><?= lang('App.progressTimeline') ?></div>
                    <div class="timeline">
                        <?php foreach ($timeline as $step): ?>
                        <div class="timeline-item">
                            <div class="timeline-dot <?= $step['completed'] ? 'completed' : ($step['current'] ? 'current' : 'pending') ?>">
                                <i class="bi <?= $step['completed'] ? 'bi-check' : $step['icon'] ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title <?= $step['pending'] ? 'pending' : '' ?>">
                                    <?= $step['label'] ?>
                                    <?php if ($step['current']): ?>
                                        <span class="badge bg-primary ms-2"><?= lang('App.current') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="timeline-desc"><?= $step['description'] ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php if ($job['status'] === 'ready_handover'): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                    <div>
                        <strong><?= lang('App.deviceReady') ?></strong><br>
                        <?= lang('App.bringIdToCollect') ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="portal-footer">
            <p><?= lang('App.needHelp') ?> <?= lang('App.contactUs') ?> <a href="tel:02-xxx-xxxx">02-XXX-XXXX</a></p>
            <p><a href="<?= base_url('login') ?>"><?= lang('App.staffLogin') ?></a></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
