<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\SystemMonitor;
use App\Libraries\DockerManager;

/**
 * System Monitor Controller
 * Provides system monitoring dashboard for Super Admin
 */
class SystemMonitorController extends BaseController
{
    private SystemMonitor $systemMonitor;
    private DockerManager $dockerManager;

    public function __construct()
    {
        $this->systemMonitor = new SystemMonitor();
        $this->dockerManager = new DockerManager();
    }

    /**
     * System monitoring dashboard
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        // Only super admin can access
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/dashboard')
                ->with('error', lang('App.accessDenied'));
        }

        $data = [
            'title' => 'System Monitoring',
            'metrics' => $this->systemMonitor->getAllMetrics(),
            'containers' => $this->dockerManager->getContainers(),
            'containerStats' => $this->dockerManager->getStats(),
        ];

        return view('admin/system_monitor/index', $this->getViewData($data));
    }

    /**
     * Get current metrics (AJAX)
     */
    public function getMetrics()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $metrics = $this->systemMonitor->getAllMetrics();
        return $this->successResponse('Metrics retrieved', $metrics);
    }

    /**
     * Get Docker containers status (AJAX)
     */
    public function getContainers()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $containers = $this->dockerManager->getContainers();
        return $this->successResponse('Containers retrieved', $containers);
    }

    /**
     * Restart container (AJAX)
     */
    public function restartContainer()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $service = $this->request->getPost('service');

        if (empty($service)) {
            return $this->errorResponse('Service name required');
        }

        // Whitelist allowed services
        $allowedServices = ['app', 'db', 'nginx'];
        if (!in_array($service, $allowedServices)) {
            return $this->errorResponse('Invalid service name');
        }

        $result = $this->dockerManager->restartContainer($service);

        if ($result['success']) {
            return $this->successResponse("Container {$service} restarted successfully", $result);
        }

        return $this->errorResponse("Failed to restart {$service}", 500, $result);
    }

    /**
     * Get container logs (AJAX)
     */
    public function getContainerLogs()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $service = $this->request->getGet('service');
        $lines = (int)($this->request->getGet('lines') ?? 100);

        if (empty($service)) {
            return $this->errorResponse('Service name required');
        }

        $logs = $this->dockerManager->getLogs($service, $lines);

        return $this->successResponse('Logs retrieved', ['logs' => $logs]);
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $cacheType = $this->request->getPost('type') ?? 'all';

        try {
            $cache = \Config\Services::cache();

            switch ($cacheType) {
                case 'all':
                    $cache->clean();
                    $message = 'All cache cleared';
                    break;
                case 'views':
                    // Clear view cache
                    $viewPath = WRITEPATH . 'cache';
                    if (is_dir($viewPath)) {
                        $this->deleteDirectory($viewPath);
                    }
                    $message = 'View cache cleared';
                    break;
                case 'session':
                    // Clear session files
                    $sessionPath = WRITEPATH . 'session';
                    if (is_dir($sessionPath)) {
                        $this->deleteDirectory($sessionPath);
                    }
                    $message = 'Session cache cleared';
                    break;
                default:
                    return $this->errorResponse('Invalid cache type');
            }

            return $this->successResponse($message);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        return true;
    }
}
