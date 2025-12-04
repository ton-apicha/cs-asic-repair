<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Log Management Controller
 * View and manage application logs
 */
class LogManagementController extends BaseController
{
    /**
     * Log viewer dashboard
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/dashboard')
                ->with('error', lang('App.accessDenied'));
        }

        $data = [
            'title' => 'Log Management',
        ];

        return view('admin/logs/index', $this->getViewData($data));
    }

    /**
     * Get application logs
     */
    public function getApplicationLogs()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $logFile = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.php';
        $lines = (int)($this->request->getGet('lines') ?? 100);
        $level = $this->request->getGet('level');

        if (!file_exists($logFile)) {
            return $this->successResponse('No logs for today', ['logs' => []]);
        }

        $content = file_get_contents($logFile);
        $logLines = explode("\n", $content);

        // Filter out PHP header
        $logLines = array_filter($logLines, function ($line) {
            return !str_starts_with($line, '<?php') &&
                !str_starts_with($line, '?>') &&
                !empty(trim($line));
        });

        // Filter by level if specified
        if ($level && $level !== 'all') {
            $logLines = array_filter($logLines, function ($line) use ($level) {
                return stripos($line, strtoupper($level)) !== false;
            });
        }

        // Get last N lines
        $logLines = array_slice($logLines, -$lines);

        return $this->successResponse('Logs retrieved', [
            'logs' => array_values($logLines),
            'count' => count($logLines),
            'date' => date('Y-m-d'),
        ]);
    }

    /**
     * Download log file
     */
    public function downloadLog()
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/dashboard')
                ->with('error', lang('App.accessDenied'));
        }

        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $logFile = WRITEPATH . 'logs/log-' . $date . '.php';

        if (!file_exists($logFile)) {
            return redirect()->back()->with('error', 'Log file not found');
        }

        return $this->response->download($logFile, null);
    }

    /**
     * Clear old logs
     */
    public function clearOldLogs()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $days = (int)($this->request->getPost('days') ?? 30);
        $logsPath = WRITEPATH . 'logs/';
        $cutoffDate = strtotime("-{$days} days");

        $deleted = 0;
        $files = glob($logsPath . 'log-*.php');

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffDate) {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }

        return $this->successResponse("Deleted {$deleted} old log files");
    }

    /**
     * Get log statistics
     */
    public function getLogStats()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $logFile = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.php';

        if (!file_exists($logFile)) {
            return $this->successResponse('Stats retrieved', [
                'errors' => 0,
                'warnings' => 0,
                'info' => 0,
                'total' => 0,
            ]);
        }

        $content = file_get_contents($logFile);

        $stats = [
            'errors' => substr_count($content, 'ERROR'),
            'warnings' => substr_count($content, 'WARNING'),
            'info' => substr_count($content, 'INFO'),
            'critical' => substr_count($content, 'CRITICAL'),
            'total' => substr_count($content, "\n") - 2, // Exclude PHP tags
        ];

        return $this->successResponse('Stats retrieved', $stats);
    }
}
