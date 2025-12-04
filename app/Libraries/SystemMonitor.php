<?php

namespace App\Libraries;

/**
 * System Monitor Library
 * Provides system metrics monitoring (CPU, RAM, Disk, Network)
 */
class SystemMonitor
{
    /**
     * Get CPU usage percentage
     */
    public function getCpuUsage(): array
    {
        $load = sys_getloadavg();
        $cpuCount = $this->getCpuCount();

        return [
            'usage_percent' => round(($load[0] / $cpuCount) * 100, 2),
            'load_1min' => $load[0],
            'load_5min' => $load[1],
            'load_15min' => $load[2],
            'cpu_count' => $cpuCount,
        ];
    }

    /**
     * Get RAM usage
     */
    public function getRamUsage(): array
    {
        $memInfo = $this->parseMemInfo();

        $total = $memInfo['MemTotal'] ?? 0;
        $free = $memInfo['MemFree'] ?? 0;
        $available = $memInfo['MemAvailable'] ?? $free;
        $used = $total - $available;

        return [
            'total_mb' => round($total / 1024, 2),
            'used_mb' => round($used / 1024, 2),
            'free_mb' => round($available / 1024, 2),
            'usage_percent' => $total > 0 ? round(($used / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get disk usage
     */
    public function getDiskUsage(string $path = '/'): array
    {
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        $used = $total - $free;

        return [
            'path' => $path,
            'total_gb' => round($total / 1024 / 1024 / 1024, 2),
            'used_gb' => round($used / 1024 / 1024 / 1024, 2),
            'free_gb' => round($free / 1024 / 1024 / 1024, 2),
            'usage_percent' => $total > 0 ? round(($used / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get all disk partitions
     */
    public function getAllDisks(): array
    {
        $disks = [];

        // Common mount points
        $mountPoints = ['/', '/var', '/home', '/tmp'];

        foreach ($mountPoints as $mount) {
            if (is_dir($mount) && is_readable($mount)) {
                try {
                    $disks[] = $this->getDiskUsage($mount);
                } catch (\Exception $e) {
                    // Skip inaccessible mounts
                    continue;
                }
            }
        }

        return $disks;
    }

    /**
     * Get network statistics
     */
    public function getNetworkStats(): array
    {
        $stats = [];

        if (file_exists('/proc/net/dev')) {
            $content = file_get_contents('/proc/net/dev');
            $lines = explode("\n", $content);

            foreach ($lines as $line) {
                if (preg_match('/^\s*(\w+):\s*(\d+)\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+(\d+)/', $line, $matches)) {
                    $interface = $matches[1];
                    if ($interface !== 'lo') { // Skip loopback
                        $stats[$interface] = [
                            'rx_bytes' => (int)$matches[2],
                            'tx_bytes' => (int)$matches[3],
                            'rx_mb' => round($matches[2] / 1024 / 1024, 2),
                            'tx_mb' => round($matches[3] / 1024 / 1024, 2),
                        ];
                    }
                }
            }
        }

        return $stats;
    }

    /**
     * Get system uptime
     */
    public function getUptime(): array
    {
        if (file_exists('/proc/uptime')) {
            $uptime = (float)file_get_contents('/proc/uptime');
            $seconds = (int)$uptime;

            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            $minutes = floor(($seconds % 3600) / 60);

            return [
                'seconds' => $seconds,
                'formatted' => sprintf('%dd %dh %dm', $days, $hours, $minutes),
                'days' => $days,
                'hours' => $hours,
                'minutes' => $minutes,
            ];
        }

        return ['seconds' => 0, 'formatted' => 'Unknown'];
    }

    /**
     * Get all system metrics
     */
    public function getAllMetrics(): array
    {
        return [
            'cpu' => $this->getCpuUsage(),
            'ram' => $this->getRamUsage(),
            'disk' => $this->getDiskUsage('/'),
            'disks' => $this->getAllDisks(),
            'network' => $this->getNetworkStats(),
            'uptime' => $this->getUptime(),
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Parse /proc/meminfo
     */
    private function parseMemInfo(): array
    {
        $memInfo = [];

        if (file_exists('/proc/meminfo')) {
            $content = file_get_contents('/proc/meminfo');
            $lines = explode("\n", $content);

            foreach ($lines as $line) {
                if (preg_match('/^(\w+):\s+(\d+)/', $line, $matches)) {
                    $memInfo[$matches[1]] = (int)$matches[2];
                }
            }
        }

        return $memInfo;
    }

    /**
     * Get CPU count
     */
    private function getCpuCount(): int
    {
        if (file_exists('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            return count($matches[0]);
        }

        return 1;
    }
}
