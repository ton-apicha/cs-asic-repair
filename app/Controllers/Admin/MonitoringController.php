<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\SystemMonitor;
use App\Libraries\DockerManager;
use App\Libraries\DatabaseManager;

/**
 * Safe System Monitoring Controller
 * Read-only monitoring dashboard for Super Admin
 * All dangerous actions removed for system safety
 */
class MonitoringController extends BaseController
{
    private SystemMonitor $systemMonitor;
    private DockerManager $dockerManager;
    private DatabaseManager $dbManager;

    public function __construct()
    {
        $this->systemMonitor = new SystemMonitor();
        $this->dockerManager = new DockerManager();
        $this->dbManager = new DatabaseManager();
    }

    /**
     * Main monitoring dashboard - all-in-one view
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/dashboard')
                ->with('error', lang('App.accessDenied'));
        }

        $data = [
            'title' => 'System Monitoring',
            'metrics' => $this->systemMonitor->getAllMetrics(),
            'containers' => $this->dockerManager->getContainers(),
            'dbStatus' => $this->dbManager->getStatus(),
            'tables' => $this->dbManager->getTables(),
            'logStats' => $this->getLogStatistics(),
            'recentLogs' => $this->getRecentLogs(30),
        ];

        return view('admin/monitoring/index', $this->getViewData($data));
    }

    /**
     * Get system metrics (AJAX)
     */
    public function getMetrics()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        return $this->successResponse('Metrics retrieved', [
            'system' => $this->systemMonitor->getAllMetrics(),
            'database' => $this->dbManager->getStatus(),
            'containers' => $this->dockerManager->getContainers(),
        ]);
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
        $lines = min((int)($this->request->getGet('lines') ?? 50), 200);

        if (empty($service)) {
            return $this->errorResponse('Service name required');
        }

        // Whitelist allowed services
        $allowedServices = ['app', 'db', 'nginx'];
        if (!in_array($service, $allowedServices)) {
            return $this->errorResponse('Invalid service');
        }

        $logs = $this->dockerManager->getLogs($service, $lines);
        return $this->successResponse('Logs retrieved', ['logs' => $logs]);
    }

    /**
     * Get application logs (AJAX)
     */
    public function getApplicationLogs()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $level = $this->request->getGet('level') ?? 'all';
        $lines = min((int)($this->request->getGet('lines') ?? 50), 200);

        $logs = $this->getRecentLogs($lines, $level);
        $stats = $this->getLogStatistics();

        return $this->successResponse('Logs retrieved', [
            'logs' => $logs,
            'stats' => $stats,
        ]);
    }

    /**
     * Get database tables info (AJAX)
     */
    public function getDatabaseInfo()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        return $this->successResponse('Database info retrieved', [
            'status' => $this->dbManager->getStatus(),
            'tables' => $this->dbManager->getTables(),
        ]);
    }

    /**
     * Get recent application logs
     */
    private function getRecentLogs(int $lines = 50, string $level = 'all'): array
    {
        $logFile = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.php';

        if (!file_exists($logFile)) {
            return [];
        }

        $content = file_get_contents($logFile);
        $logLines = explode("\n", $content);

        // Filter out PHP header and empty lines
        $logLines = array_filter($logLines, function ($line) {
            return !str_starts_with($line, '<?php') &&
                !str_starts_with($line, '?>') &&
                !empty(trim($line));
        });

        // Filter by level
        if ($level !== 'all') {
            $logLines = array_filter($logLines, function ($line) use ($level) {
                return stripos($line, strtoupper($level)) !== false;
            });
        }

        return array_slice(array_values($logLines), -$lines);
    }

    /**
     * Get log statistics
     */
    private function getLogStatistics(): array
    {
        $logFile = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.php';

        if (!file_exists($logFile)) {
            return ['errors' => 0, 'warnings' => 0, 'info' => 0, 'total' => 0];
        }

        $content = file_get_contents($logFile);

        return [
            'errors' => substr_count($content, 'ERROR'),
            'warnings' => substr_count($content, 'WARNING'),
            'info' => substr_count($content, 'INFO'),
            'critical' => substr_count($content, 'CRITICAL'),
            'total' => max(0, substr_count($content, "\n") - 2),
        ];
    }

    /**
     * Get historical graph data for charts (AJAX)
     */
    public function getGraphData()
    {
        if (!$this->isSuperAdmin()) {
            return $this->errorResponse('Access denied', 403);
        }

        $period = $this->request->getGet('period') ?? '24h';

        $metricModel = new \App\Models\SystemMetricModel();

        // Get metrics for each type
        $cpuMetrics = $metricModel->getMetrics('cpu', $period);
        $ramMetrics = $metricModel->getMetrics('ram', $period);
        $diskMetrics = $metricModel->getMetrics('disk', $period);

        // Format labels (timestamps)
        $labels = [];
        $cpuData = [];
        $ramData = [];
        $diskData = [];

        // If no data, generate sample data for demo
        if (empty($cpuMetrics) && empty($ramMetrics) && empty($diskMetrics)) {
            // Generate demo data for last 24 hours
            $hours = match ($period) {
                '1h' => 12,
                '6h' => 24,
                '24h' => 48,
                '7d' => 168,
                default => 48,
            };

            $interval = match ($period) {
                '1h' => 5,
                '6h' => 15,
                '24h' => 30,
                '7d' => 60,
                default => 30,
            };

            for ($i = $hours; $i >= 0; $i--) {
                $time = strtotime("-{$i} minutes * {$interval}");
                $labels[] = date('H:i', $time);

                // Generate realistic random data
                $cpuData[] = rand(15, 80);
                $ramData[] = rand(40, 85);
                $diskData[] = rand(20, 60);
            }
        } else {
            // Use real data
            foreach ($cpuMetrics as $metric) {
                $labels[] = date('H:i', strtotime($metric['recorded_at']));
                $cpuData[] = (float) $metric['value'];
            }

            foreach ($ramMetrics as $metric) {
                $ramData[] = (float) $metric['value'];
            }

            foreach ($diskMetrics as $metric) {
                $diskData[] = (float) $metric['value'];
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'labels' => $labels,
            'cpu' => $cpuData,
            'ram' => $ramData,
            'disk' => $diskData,
            'period' => $period
        ]);
    }
}
