<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ASIC Repair Management System">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="alternate icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('favicon.svg') ?>">

    <title><?= $title ?? 'ASIC Repair' ?> - R-POS</title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- jQuery UI CSS -->
    <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/style.css') ?>?v=<?= time() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/spinner.css') ?>?v=<?= time() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/print.css') ?>?v=<?= time() ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/charts.css') ?>?v=<?= time() ?>" rel="stylesheet">

    <?= $this->renderSection('styles') ?>
</head>

<body class="<?= isset($user) && $user ? 'has-sidebar' : '' ?>">
    <?php if (isset($user) && $user): ?>
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <!-- Sidebar Header / Logo -->
            <div class="sidebar-header">
                <a href="<?= base_url('dashboard') ?>" class="sidebar-brand">
                    <i class="bi bi-cpu-fill brand-icon"></i>
                    <span class="brand-text">ASIC R-POS</span>
                </a>
                <button type="button" class="sidebar-toggle d-lg-none" id="sidebarClose">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Sidebar Menu -->
            <nav class="sidebar-nav">
                <ul class="sidebar-menu">
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="<?= base_url('dashboard') ?>" class="menu-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                            <i class="bi bi-speedometer2 menu-icon"></i>
                            <span class="menu-text"><?= lang('App.dashboard') ?></span>
                        </a>
                    </li>

                    <!-- Jobs -->
                    <li class="menu-item has-submenu <?= str_starts_with(uri_string(), 'jobs') ? 'open' : '' ?>">
                        <a href="#" class="menu-link submenu-toggle">
                            <i class="bi bi-clipboard-check menu-icon"></i>
                            <span class="menu-text"><?= lang('App.jobs') ?></span>
                            <i class="bi bi-chevron-down submenu-arrow"></i>
                        </a>
                        <ul class="submenu <?= str_starts_with(uri_string(), 'jobs') ? 'show' : '' ?>">
                            <li><a href="<?= base_url('jobs') ?>" class="<?= uri_string() === 'jobs' ? 'active' : '' ?>"><?= lang('App.allJobs') ?></a></li>
                            <li><a href="<?= base_url('jobs/kanban') ?>" class="<?= uri_string() === 'jobs/kanban' ? 'active' : '' ?>"><?= lang('App.kanbanBoard') ?></a></li>
                            <li><a href="<?= base_url('jobs/create') ?>" class="<?= uri_string() === 'jobs/create' ? 'active' : '' ?>"><?= lang('App.newJob') ?></a></li>
                        </ul>
                    </li>

                    <!-- Customers -->
                    <li class="menu-item">
                        <a href="<?= base_url('customers') ?>" class="menu-link <?= str_starts_with(uri_string(), 'customers') ? 'active' : '' ?>">
                            <i class="bi bi-people menu-icon"></i>
                            <span class="menu-text"><?= lang('App.customers') ?></span>
                        </a>
                    </li>

                    <!-- Assets -->
                    <li class="menu-item">
                        <a href="<?= base_url('machines') ?>" class="menu-link <?= str_starts_with(uri_string(), 'machines') ? 'active' : '' ?>">
                            <i class="bi bi-hdd-rack menu-icon"></i>
                            <span class="menu-text"><?= lang('App.assets') ?></span>
                        </a>
                    </li>

                    <!-- Inventory -->
                    <li class="menu-item">
                        <a href="<?= base_url('inventory') ?>" class="menu-link <?= str_starts_with(uri_string(), 'inventory') ? 'active' : '' ?>">
                            <i class="bi bi-box-seam menu-icon"></i>
                            <span class="menu-text"><?= lang('App.inventory') ?></span>
                        </a>
                    </li>

                    <?php if ($isAdmin ?? false): ?>
                        <!-- Quotations -->
                        <li class="menu-item <?= str_starts_with(uri_string(), 'quotations') ? 'active' : '' ?>">
                            <a href="<?= base_url('quotations') ?>" class="menu-link <?= str_starts_with(uri_string(), 'quotations') ? 'active' : '' ?>">
                                <i class="bi bi-file-earmark-text menu-icon"></i>
                                <span class="menu-text"><?= lang('App.quotations') ?></span>
                            </a>
                        </li>

                        <!-- Divider -->
                        <li class="menu-divider">
                            <span><?= lang('App.management') ?? 'Management' ?></span>
                        </li>

                        <!-- Reports (Admin only) -->
                        <li class="menu-item has-submenu <?= str_starts_with(uri_string(), 'reports') ? 'open' : '' ?>">
                            <a href="#" class="menu-link submenu-toggle">
                                <i class="bi bi-graph-up menu-icon"></i>
                                <span class="menu-text"><?= lang('App.reports') ?></span>
                                <i class="bi bi-chevron-down submenu-arrow"></i>
                            </a>
                            <ul class="submenu <?= str_starts_with(uri_string(), 'reports') ? 'show' : '' ?>">
                                <li><a href="<?= base_url('reports/sales') ?>"><?= lang('App.salesReport') ?></a></li>
                                <li><a href="<?= base_url('reports/profit') ?>"><?= lang('App.profitReport') ?></a></li>
                                <li><a href="<?= base_url('reports/warranty') ?>"><?= lang('App.warrantyReport') ?></a></li>
                                <li><a href="<?= base_url('reports/wip') ?>"><?= lang('App.wipReport') ?></a></li>
                                <li><a href="<?= base_url('reports/kpi') ?>"><?= lang('App.kpiReport') ?></a></li>
                            </ul>
                        </li>

                        <!-- Settings (Admin only) -->
                        <li class="menu-item has-submenu <?= str_starts_with(uri_string(), 'settings') ? 'open' : '' ?>">
                            <a href="#" class="menu-link submenu-toggle">
                                <i class="bi bi-gear menu-icon"></i>
                                <span class="menu-text"><?= lang('App.settings') ?></span>
                                <i class="bi bi-chevron-down submenu-arrow"></i>
                            </a>
                            <ul class="submenu <?= str_starts_with(uri_string(), 'settings') || str_starts_with(uri_string(), 'admin/monitoring') ? 'show' : '' ?>">
                                <li><a href="<?= base_url('settings') ?>"><?= lang('App.systemSettings') ?></a></li>
                                <li><a href="<?= base_url('settings/branches') ?>"><?= lang('App.branches') ?></a></li>
                                <li><a href="<?= base_url('settings/users') ?>"><?= lang('App.users') ?></a></li>
                                <li><a href="<?= base_url('settings/backup') ?>"><i class="bi bi-database me-1"></i><?= lang('App.backup') ?></a></li>
                                <?php if ($isSuperAdmin ?? false): ?>
                                    <li><a href="<?= base_url('admin/monitoring') ?>" class="<?= str_starts_with(uri_string(), 'admin/monitoring') ? 'active' : '' ?>">
                                            <i class="bi bi-speedometer2 me-1"></i>System Monitoring
                                        </a></li>
                                    <li><a href="<?= base_url('admin/activity-log') ?>" class="<?= str_starts_with(uri_string(), 'admin/activity-log') ? 'active' : '' ?>">
                                            <i class="bi bi-activity me-1"></i><?= lang('App.activityLog') ?? 'Activity Log' ?>
                                        </a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <!-- Sidebar Footer / User Info -->
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?= esc($user['name'] ?? $user['username']) ?></span>
                        <span class="user-role badge bg-<?= $user['role'] === 'super_admin' ? 'warning' : ($user['role'] === 'admin' ? 'danger' : 'info') ?>">
                            <?= $user['role'] === 'super_admin' ? lang('App.roleSuperAdmin') : ucfirst($user['role'] ?? 'user') ?>
                        </span>
                    </div>
                    <a href="<?= base_url('logout') ?>" class="logout-btn" title="<?= lang('App.logout') ?>">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Sidebar Backdrop (Mobile) -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Main Content Wrapper -->
        <div class="main-wrapper">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-left">
                    <!-- Sidebar Toggle (Mobile) -->
                    <button type="button" class="sidebar-toggle-btn d-lg-none" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <!-- Sidebar Collapse (Desktop) -->
                    <button type="button" class="sidebar-collapse-btn d-none d-lg-flex" id="sidebarCollapse" title="Toggle Sidebar">
                        <i class="bi bi-layout-sidebar-inset"></i>
                    </button>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="d-none d-md-block">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                            <?php if (isset($breadcrumb)): ?>
                                <?php foreach ($breadcrumb as $item): ?>
                                    <?php if ($item['active'] ?? false): ?>
                                        <li class="breadcrumb-item active"><?= esc($item['title']) ?></li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item"><a href="<?= $item['url'] ?>"><?= esc($item['title']) ?></a></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="breadcrumb-item active"><?= esc($title ?? 'Dashboard') ?></li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>

                <div class="topbar-right">
                    <?php if (($isSuperAdmin ?? false) && !empty($allBranches)): ?>
                        <!-- Branch Selector for Super Admin -->
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-building me-1"></i>
                                <span class="d-none d-sm-inline">
                                    <?php
                                    $selectedBranch = session()->get('filter_branch_id');
                                    if ($selectedBranch) {
                                        foreach ($allBranches as $b) {
                                            if ($b['id'] == $selectedBranch) {
                                                echo esc($b['name']);
                                                break;
                                            }
                                        }
                                    } else {
                                        echo lang('App.allBranches');
                                    }
                                    ?>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header"><i class="bi bi-filter me-1"></i><?= lang('App.filterByBranch') ?></h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item <?= !$selectedBranch ? 'active' : '' ?>" href="<?= base_url('branch/switch/all') ?>">
                                        <i class="bi bi-globe me-2"></i><?= lang('App.allBranches') ?>
                                    </a>
                                </li>
                                <?php foreach ($allBranches as $b): ?>
                                    <li>
                                        <a class="dropdown-item <?= $selectedBranch == $b['id'] ? 'active' : '' ?>" href="<?= base_url('branch/switch/' . $b['id']) ?>">
                                            <i class="bi bi-building me-2"></i><?= esc($b['name']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Dark Mode Toggle -->
                    <button class="theme-toggle topbar-btn" id="themeToggle" title="Toggle Dark Mode">
                        <i class="bi bi-sun-fill"></i>
                        <i class="bi bi-moon-fill"></i>
                    </button>

                    <!-- Language Switcher -->
                    <div class="dropdown">
                        <button class="topbar-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe"></i>
                            <span class="d-none d-sm-inline ms-1">
                                <?php
                                $currentLocale = service('request')->getLocale();
                                echo match ($currentLocale) {
                                    'th' => 'TH',
                                    'zh' => 'CN',
                                    default => 'EN'
                                };
                                ?>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item <?= $currentLocale === 'en' ? 'active' : '' ?>" href="<?= base_url('language/switch/en') ?>">
                                    🇺🇸 <?= lang('App.english') ?>
                                </a></li>
                            <li><a class="dropdown-item <?= $currentLocale === 'zh' ? 'active' : '' ?>" href="<?= base_url('language/switch/zh') ?>">
                                    🇨🇳 <?= lang('App.chinese') ?>
                                </a></li>
                            <li><a class="dropdown-item <?= $currentLocale === 'th' ? 'active' : '' ?>" href="<?= base_url('language/switch/th') ?>">
                                    🇹🇭 <?= lang('App.thai') ?>
                                </a></li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="dropdown">
                        <button class="topbar-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Quick Actions">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">Quick Actions</h6>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('jobs/create') ?>">
                                    <i class="bi bi-clipboard-plus me-2"></i>New Job Card
                                </a></li>
                            <li><a class="dropdown-item" href="<?= base_url('customers/create') ?>">
                                    <i class="bi bi-person-plus me-2"></i>Add Customer
                                </a></li>
                            <li><a class="dropdown-item" href="<?= base_url('machines/create') ?>">
                                    <i class="bi bi-hdd-rack me-2"></i>Register Asset
                                </a></li>
                        </ul>
                    </div>

                    <!-- User Menu (Mobile) -->
                    <div class="dropdown d-lg-none">
                        <button class="topbar-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text">
                                    <strong><?= esc($user['name'] ?? $user['username']) ?></strong><br>
                                    <small class="text-muted"><?= ucfirst($user['role'] ?? 'user') ?></small>
                                </span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i><?= lang('App.logout') ?>
                                </a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div class="flash-messages">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Main Content -->
            <main class="main-content">
                <?= $this->renderSection('content') ?>
            </main>

            <!-- Modals (outside main for proper z-index) -->
            <?= $this->renderSection('modals') ?>

            <!-- Footer -->
            <footer class="main-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span>&copy; <?= date('Y') ?> ASIC Repair Management System</span>
                    <span class="text-muted">Version 1.1.0</span>
                </div>
            </footer>

            <!-- Mobile Bottom Navigation -->
            <nav class="mobile-bottom-nav">
                <ul class="mobile-bottom-nav-items">
                    <li class="mobile-nav-item">
                        <a href="<?= base_url('dashboard') ?>" class="mobile-nav-link <?= current_url() == base_url('dashboard') ? 'active' : '' ?>">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="<?= base_url('jobs') ?>" class="mobile-nav-link <?= strpos(current_url(), '/jobs') !== false ? 'active' : '' ?>">
                            <i class="bi bi-clipboard-check"></i>
                            <span>Jobs</span>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="<?= base_url('jobs/create') ?>" class="mobile-nav-link" style="background: #3b82f6; color: #fff; border-radius: 50%; width: 44px; height: 44px; margin: -10px 0;">
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="<?= base_url('customers') ?>" class="mobile-nav-link <?= strpos(current_url(), '/customers') !== false ? 'active' : '' ?>">
                            <i class="bi bi-people"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="<?= base_url('inventory') ?>" class="mobile-nav-link <?= strpos(current_url(), '/inventory') !== false ? 'active' : '' ?>">
                            <i class="bi bi-box-seam"></i>
                            <span>Inventory</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php else: ?>
        <!-- Non-authenticated layout (Login page, etc.) -->
        <main>
            <?= $this->renderSection('content') ?>
        </main>
    <?php endif; ?>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <div id="appToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-info-circle me-2" id="toastIcon"></i>
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastBody"></div>
        </div>
    </div>

    <!-- Global Loading Spinner -->
    <div class="spinner-overlay" id="globalSpinner" style="display: none;" role="status" aria-label="กำลังโหลด">
        <div class="spinner-container">
            <div class="spinner-circle"></div>
            <span class="spinner-text" id="spinnerText">กำลังโหลด...</span>
        </div>
    </div>

    <!-- Debug Info -->
    <script>
        console.log('=== ASIC Repair System Debug Info ===');
        console.log('Base URL:', '<?= base_url() ?>');
        console.log('Current URL:', window.location.href);
        console.log('Page loaded at:', new Date().toISOString());
    </script>

    <!-- jQuery 3.7 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- SortableJS for Kanban -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/app.js') ?>?v=<?= time() ?>"></script>
    <script src="<?= base_url('assets/js/print-preview.js') ?>?v=<?= time() ?>"></script>
    <script src="<?= base_url('assets/js/shortcuts.js') ?>?v=<?= time() ?>"></script>
    <script src="<?= base_url('assets/js/analytics.js') ?>?v=<?= time() ?>"></script>

    <script>
        // CSRF Setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="<?= csrf_token() ?>"]').attr('content')
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.flash-messages .alert').fadeOut('slow');
        }, 5000);
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>