<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\DatabaseManager;

/**
 * Database Management Controller
 * Provides database monitoring and management tools for Super Admin
 */
class DatabaseManagementController extends BaseController
{
    private DatabaseManager $dbManager;

    public function __construct()
    {
        $this->dbManager = new DatabaseManager();
    }

    /**
     * Database management dashboard
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/dashboard')
                ->with('error', lang('App.accessDenied'));
        }

        $data = [
            'title' => 'Database Management',
            'status' => $this->dbManager->getStatus(),
            'tables' => $this->dbManager->getTables(),
        ];

        return view('admin/database/index', $this->getViewData($data));
    }

    /**
     * Get database status (AJAX)
     */
    public function getStatus()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $status = $this->dbManager->getStatus();
        return $this->successResponse('Status retrieved', $status);
    }

    /**
     * Get all tables
     */
    public function getTables()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $tables = $this->dbManager->getTables();
        return $this->successResponse('Tables retrieved', $tables);
    }

    /**
     * Optimize table
     */
    public function optimizeTable()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $table = $this->request->getPost('table');

        if (empty($table)) {
            return $this->errorResponse('Table name required');
        }

        $result = $this->dbManager->optimizeTable($table);

        if ($result['success']) {
            return $this->successResponse($result['message']);
        }

        return $this->errorResponse($result['message']);
    }

    /**
     * Optimize all tables
     */
    public function optimizeAll()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $results = $this->dbManager->optimizeAllTables();

        $success = array_filter($results, fn($r) => $r['success']);
        $failed = array_filter($results, fn($r) => !$r['success']);

        return $this->successResponse(
            sprintf('Optimized %d tables, %d failed', count($success), count($failed)),
            ['results' => $results]
        );
    }

    /**
     * Get active connections
     */
    public function getConnections()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $connections = $this->dbManager->getConnections();
        return $this->successResponse('Connections retrieved', $connections);
    }

    /**
     * Kill a process
     */
    public function killProcess()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $processId = (int)$this->request->getPost('process_id');

        if (empty($processId)) {
            return $this->errorResponse('Process ID required');
        }

        $result = $this->dbManager->killProcess($processId);

        if ($result['success']) {
            return $this->successResponse($result['message']);
        }

        return $this->errorResponse($result['message']);
    }

    /**
     * Get slow queries
     */
    public function getSlowQueries()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $limit = (int)($this->request->getGet('limit') ?? 10);
        $queries = $this->dbManager->getSlowQueries($limit);

        return $this->successResponse('Slow queries retrieved', $queries);
    }
}
