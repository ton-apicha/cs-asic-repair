<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================================
// Authentication Routes
// ============================================================================
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

// ============================================================================
// Protected Routes (Require Authentication)
// ============================================================================
$routes->group('', ['filter' => 'auth'], function ($routes) {
    
    // Dashboard
    $routes->get('dashboard', 'DashboardController::index');
    
    // ========================================================================
    // Customer Management
    // ========================================================================
    $routes->group('customers', function ($routes) {
        $routes->get('/', 'CustomerController::index');
        $routes->get('create', 'CustomerController::create');
        $routes->post('store', 'CustomerController::store');
        $routes->get('edit/(:num)', 'CustomerController::edit/$1');
        $routes->post('update/(:num)', 'CustomerController::update/$1');
        $routes->post('delete/(:num)', 'CustomerController::delete/$1');
        $routes->get('view/(:num)', 'CustomerController::view/$1');
        $routes->get('search', 'CustomerController::search');
        $routes->get('history/(:num)', 'CustomerController::history/$1');
    });
    
    // ========================================================================
    // Asset Management (renamed to 'machines' to avoid conflict with public/assets folder)
    // ========================================================================
    $routes->group('machines', function ($routes) {
        $routes->get('/', 'AssetController::index');
        $routes->get('create', 'AssetController::create');
        $routes->post('store', 'AssetController::store');
        $routes->get('edit/(:num)', 'AssetController::edit/$1');
        $routes->post('update/(:num)', 'AssetController::update/$1');
        $routes->post('delete/(:num)', 'AssetController::delete/$1');
        $routes->get('view/(:num)', 'AssetController::view/$1');
        $routes->get('search', 'AssetController::search');
        $routes->get('check-serial', 'AssetController::checkSerial');
    });
    
    // ========================================================================
    // Job Card Management
    // ========================================================================
    $routes->group('jobs', function ($routes) {
        $routes->get('/', 'JobController::index');
        $routes->get('kanban', 'JobController::kanban');
        $routes->get('create', 'JobController::create');
        $routes->get('create-from-asset/(:num)', 'JobController::createFromAsset/$1');
        $routes->post('store', 'JobController::store');
        $routes->get('edit/(:num)', 'JobController::edit/$1');
        $routes->post('update/(:num)', 'JobController::update/$1');
        $routes->post('update-status/(:num)', 'JobController::updateStatus/$1');
        $routes->post('cancel/(:num)', 'JobController::cancel/$1');
        $routes->get('view/(:num)', 'JobController::view/$1');
        $routes->get('print/(:num)', 'JobController::print/$1');
        $routes->get('print-label/(:num)', 'JobController::printLabel/$1');
        $routes->get('export-pdf/(:num)', 'JobController::exportPdf/$1');
        $routes->get('export-receipt/(:num)', 'JobController::exportReceipt/$1');
        
        // Job Parts
        $routes->post('add-part/(:num)', 'JobController::addPart/$1');
        $routes->post('remove-part/(:num)', 'JobController::removePart/$1');
        
        // Symptoms
        $routes->get('symptoms', 'JobController::getSymptoms');
    });
    
    // ========================================================================
    // Inventory Management
    // ========================================================================
    $routes->group('inventory', function ($routes) {
        $routes->get('/', 'InventoryController::index');
        $routes->get('create', 'InventoryController::create');
        $routes->post('store', 'InventoryController::store');
        $routes->get('edit/(:num)', 'InventoryController::edit/$1');
        $routes->post('update/(:num)', 'InventoryController::update/$1');
        $routes->post('delete/(:num)', 'InventoryController::delete/$1');
        $routes->get('view/(:num)', 'InventoryController::view/$1');
        $routes->get('search', 'InventoryController::search');
        $routes->get('low-stock', 'InventoryController::lowStock');
        $routes->get('transactions/(:num)', 'InventoryController::transactions/$1');
    });
    
    // ========================================================================
    // Payment Management
    // ========================================================================
    $routes->group('payments', function ($routes) {
        $routes->get('/', 'PaymentController::index');
        $routes->post('store', 'PaymentController::store');
        $routes->get('job/(:num)', 'PaymentController::byJob/$1');
    });
    
    // ========================================================================
    // Reports
    // ========================================================================
    $routes->group('reports', function ($routes) {
        $routes->get('/', 'ReportController::index');
        $routes->get('sales', 'ReportController::sales');
        $routes->get('profit', 'ReportController::profit');
        $routes->get('warranty', 'ReportController::warranty');
        $routes->get('wip', 'ReportController::wip');
        $routes->get('parts-usage', 'ReportController::partsUsage');
        $routes->get('kpi', 'ReportController::kpi');
    });
    
    // ========================================================================
    // Settings (Admin Only)
    // ========================================================================
    $routes->group('settings', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/', 'SettingController::index');
        $routes->post('update', 'SettingController::update');
        
        // Branch Management
        $routes->get('branches', 'SettingController::branches');
        $routes->post('branches/store', 'SettingController::storeBranch');
        $routes->post('branches/update/(:num)', 'SettingController::updateBranch/$1');
        $routes->post('branches/delete/(:num)', 'SettingController::deleteBranch/$1');
        
        // User Management
        $routes->get('users', 'SettingController::users');
        $routes->get('users/create', 'SettingController::createUser');
        $routes->post('users/store', 'SettingController::storeUser');
        $routes->get('users/edit/(:num)', 'SettingController::editUser/$1');
        $routes->post('users/update/(:num)', 'SettingController::updateUser/$1');
        $routes->post('users/delete/(:num)', 'SettingController::deleteUser/$1');
    });
    
    // ========================================================================
    // Language Switch
    // ========================================================================
    $routes->get('language/(:segment)', 'LanguageController::switch/$1');
    
    // ========================================================================
    // API Endpoints (for AJAX)
    // ========================================================================
    $routes->group('api', function ($routes) {
        $routes->get('dashboard/stats', 'DashboardController::getStats');
        $routes->get('jobs/by-status', 'JobController::getByStatus');
        $routes->post('jobs/update-order', 'JobController::updateOrder');
    });
});

