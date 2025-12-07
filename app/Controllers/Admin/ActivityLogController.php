<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\UserModel;

class ActivityLogController extends BaseController
{
    protected $auditLogModel;
    protected $userModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display activity log list
     */
    public function index()
    {
        // Get filters from request
        $userId = $this->request->getGet('user_id');
        $action = $this->request->getGet('action');
        $tableName = $this->request->getGet('table');
        $from = $this->request->getGet('from') ?? date('Y-m-d', strtotime('-7 days'));
        $to = $this->request->getGet('to') ?? date('Y-m-d');
        $limit = (int) ($this->request->getGet('limit') ?? 100);

        // Build query
        $builder = $this->auditLogModel
            ->select('audit_logs.*, users.name as user_name, users.username')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->where('DATE(audit_logs.created_at) >=', $from)
            ->where('DATE(audit_logs.created_at) <=', $to);

        if ($userId) {
            $builder->where('audit_logs.user_id', $userId);
        }

        if ($action) {
            $builder->where('audit_logs.action', $action);
        }

        if ($tableName) {
            $builder->where('audit_logs.table_name', $tableName);
        }

        $logs = $builder
            ->orderBy('audit_logs.created_at', 'DESC')
            ->limit($limit)
            ->findAll();

        // Get users for filter dropdown
        $users = $this->userModel->findAll();

        // Get distinct tables for filter dropdown
        $tables = $this->auditLogModel
            ->select('table_name')
            ->distinct()
            ->where('table_name IS NOT NULL')
            ->findAll();

        // Get stats
        $stats = [
            'total_today' => $this->auditLogModel
                ->where('DATE(created_at)', date('Y-m-d'))
                ->countAllResults(),
            'creates_today' => $this->auditLogModel
                ->where('DATE(created_at)', date('Y-m-d'))
                ->where('action', 'CREATE')
                ->countAllResults(),
            'updates_today' => $this->auditLogModel
                ->where('DATE(created_at)', date('Y-m-d'))
                ->where('action', 'UPDATE')
                ->countAllResults(),
            'deletes_today' => $this->auditLogModel
                ->where('DATE(created_at)', date('Y-m-d'))
                ->where('action', 'DELETE')
                ->countAllResults(),
        ];

        return view('admin/activity_log/index', [
            'logs' => $logs,
            'users' => $users,
            'tables' => $tables,
            'stats' => $stats,
            'filters' => [
                'user_id' => $userId,
                'action' => $action,
                'table' => $tableName,
                'from' => $from,
                'to' => $to,
                'limit' => $limit,
            ],
        ]);
    }

    /**
     * View details of a specific log entry
     */
    public function view(int $id)
    {
        $log = $this->auditLogModel
            ->select('audit_logs.*, users.name as user_name, users.username')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->find($id);

        if (!$log) {
            return redirect()->to('/admin/activity-log')->with('error', 'ไม่พบข้อมูล Activity Log');
        }

        return view('admin/activity_log/view', [
            'log' => $log,
        ]);
    }

    /**
     * Get activity logs for a specific record (AJAX)
     */
    public function getRecordLogs()
    {
        $tableName = $this->request->getGet('table');
        $recordId = (int) $this->request->getGet('record_id');

        if (!$tableName || !$recordId) {
            return $this->response->setJSON(['error' => 'Missing parameters']);
        }

        $logs = $this->auditLogModel->getLogsForRecord($tableName, $recordId, 50);

        return $this->response->setJSON(['logs' => $logs]);
    }
}
