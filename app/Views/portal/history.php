<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - ASIC Repair</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
            min-height: 100vh;
        }
        
        .history-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
            color: #fff;
        }
        
        .search-card, .result-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .job-card {
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }
        
        .job-card:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }
        
        .job-id {
            font-weight: 700;
            color: #3b82f6;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
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

    <div class="history-container">
        <div class="brand-header">
            <h1><i class="bi bi-cpu-fill me-2"></i>ASIC Repair</h1>
            <p class="text-white-50"><?= lang('App.repairHistory') ?></p>
        </div>
        
        <div class="search-card">
            <h5><i class="bi bi-clock-history me-2"></i><?= lang('App.viewHistory') ?></h5>
            <form method="GET" class="mt-3">
                <div class="input-group">
                    <input type="tel" class="form-control form-control-lg" name="phone" 
                           value="<?= esc($phone ?? '') ?>" placeholder="<?= lang('App.enterPhone') ?>">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <?php if ($customer): ?>
        <div class="result-card">
            <h5 class="mb-3">
                <i class="bi bi-person-circle me-2"></i>
                <?= esc($customer['name']) ?>
            </h5>
            
            <?php if (empty($jobs)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                    <p><?= lang('App.noRepairHistory') ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($jobs as $job): ?>
                <a href="<?= base_url('track?job_id=' . $job['job_id']) ?>" class="text-decoration-none">
                    <div class="job-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="job-id"><?= esc($job['job_id']) ?></div>
                                <div class="text-muted small"><?= esc($job['brand_model']) ?></div>
                                <div class="text-muted small mt-1"><?= date('d M Y', strtotime($job['created_at'])) ?></div>
                            </div>
                            <div class="text-end">
                                <?php
                                $statusClass = match($job['status']) {
                                    'new_checkin', 'pending_repair' => 'bg-warning text-dark',
                                    'in_progress' => 'bg-primary',
                                    'repair_done', 'ready_handover' => 'bg-success',
                                    'delivered' => 'bg-secondary',
                                    default => 'bg-secondary'
                                };
                                $statusText = match($job['status']) {
                                    'new_checkin' => lang('App.checkedIn'),
                                    'pending_repair' => lang('App.awaitingRepair'),
                                    'in_progress' => lang('App.repairInProgress'),
                                    'repair_done' => lang('App.repairComplete'),
                                    'ready_handover' => lang('App.readyForPickup'),
                                    'delivered' => lang('App.delivered'),
                                    default => ucfirst(str_replace('_', ' ', $job['status']))
                                };
                                ?>
                                <span class="badge <?= $statusClass ?> status-badge"><?= $statusText ?></span>
                                <div class="text-muted small mt-2">฿<?= number_format($job['grand_total'], 0) ?></div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="<?= base_url('track') ?>" class="text-white-50 text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?> <?= lang('App.trackRepair') ?>
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
