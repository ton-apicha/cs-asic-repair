<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * System Metrics Model
 * Store historical system metrics
 */
class SystemMetricModel extends Model
{
    protected $table = 'system_metrics';
    protected $primaryKey = 'id';
    protected $allowedFields = ['metric_type', 'value', 'details', 'recorded_at'];
    protected $useTimestamps = false;

    /**
     * Record a metric
     */
    public function recordMetric(string $type, float $value, ?array $details = null): bool
    {
        return $this->insert([
            'metric_type' => $type,
            'value' => $value,
            'details' => $details ? json_encode($details) : null,
            'recorded_at' => date('Y-m-d H:i:s'),
        ]) !== false;
    }

    /**
     * Get metrics for a time period
     */
    public function getMetrics(string $type, string $period = '24h'): array
    {
        $hours = match($period) {
            '1h' => 1,
            '6h' => 6,
            '24h' => 24,
            '7d' => 168,
            '30d' => 720,
            default => 24,
        };
        
        $startTime = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
        
        return $this->where('metric_type', $type)
            ->where('recorded_at >=', $start Time)
            ->orderBy('recorded_at', 'ASC')
            ->findAll();
    }

    /**
     * Clean old metrics (keep last 30 days)
     */
    public function cleanOldMetrics(): int
    {
        $cutoff = date('Y-m-d H:i:s', strtotime('-30 days'));
        return $this->where('recorded_at <', $cutoff)->delete();
    }
}
