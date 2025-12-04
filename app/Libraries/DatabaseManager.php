<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

/**
 * Database Manager Library
 * Provides database monitoring and management tools
 */
class DatabaseManager
{
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Get database size
     */
    public function getDatabaseSize(): array
    {
        $dbName = $this->db->database;

        $query = $this->db->query("
            SELECT 
                table_schema AS 'database_name',
                SUM(data_length + index_length) / 1024 / 1024 AS 'size_mb'
            FROM information_schema.TABLES 
            WHERE table_schema = ?
            GROUP BY table_schema
        ", [$dbName]);

        $result = $query->getRowArray();

        return [
            'database' => $dbName,
            'size_mb' => round($result['size_mb'] ?? 0, 2),
            'size_gb' => round(($result['size_mb'] ?? 0) / 1024, 3),
        ];
    }

    /**
     * Get all tables with sizes
     */
    public function getTables(): array
    {
        $dbName = $this->db->database;

        $query = $this->db->query("
            SELECT 
                table_name,
                ROUND((data_length + index_length) / 1024 / 1024, 2) AS size_mb,
                table_rows,
                engine,
                create_time,
                update_time
            FROM information_schema.TABLES
            WHERE table_schema = ?
            ORDER BY (data_length + index_length) DESC
        ", [$dbName]);

        return $query->getResultArray();
    }

    /**
     * Get active database connections
     */
    public function getConnections(): array
    {
        $query = $this->db->query("SHOW PROCESSLIST");
        $processes = $query->getResultArray();

        $active = 0;
        $sleeping = 0;

        foreach ($processes as $process) {
            if ($process['Command'] === 'Query') {
                $active++;
            } elseif ($process['Command'] === 'Sleep') {
                $sleeping++;
            }
        }

        return [
            'total' => count($processes),
            'active' => $active,
            'sleeping' => $sleeping,
            'max_connections' => $this->getMaxConnections(),
            'processes' => $processes,
        ];
    }

    /**
     * Get max connections from MySQL config
     */
    public function getMaxConnections(): int
    {
        $query = $this->db->query("SHOW VARIABLES LIKE 'max_connections'");
        $result = $query->getRowArray();
        return (int)($result['Value'] ?? 151);
    }

    /**
     * Optimize a table
     */
    public function optimizeTable(string $table): array
    {
        try {
            $this->db->query("OPTIMIZE TABLE " . $this->db->escapeIdentifiers($table));
            return ['success' => true, 'message' => "Table {$table} optimized successfully"];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Optimize all tables
     */
    public function optimizeAllTables(): array
    {
        $tables = $this->getTables();
        $results = [];

        foreach ($tables as $table) {
            $result = $this->optimizeTable($table['table_name']);
            $results[] = array_merge(['table' => $table['table_name']], $result);
        }

        return $results;
    }

    /**
     * Analyze a table
     */
    public function analyzeTable(string $table): array
    {
        try {
            $this->db->query("ANALYZE TABLE " . $this->db->escapeIdentifiers($table));
            return ['success' => true, 'message' => "Table {$table} analyzed successfully"];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get slow queries (if slow query log is enabled)
     */
    public function getSlowQueries(int $limit = 10): array
    {
        // Check if slow query log is enabled
        $query = $this->db->query("SHOW VARIABLES LIKE 'slow_query_log'");
        $result = $query->getRowArray();

        if (($result['Value'] ?? 'OFF') === 'OFF') {
            return [
                'enabled' => false,
                'queries' => [],
                'message' => 'Slow query log is not enabled'
            ];
        }

        // Get slow queries from performance schema if available
        try {
            $query = $this->db->query("
                SELECT 
                    DIGEST_TEXT as query,
                    COUNT_STAR as exec_count,
                    AVG_TIMER_WAIT / 1000000000000 as avg_time_sec,
                    MAX_TIMER_WAIT / 1000000000000 as max_time_sec
                FROM performance_schema.events_statements_summary_by_digest
                WHERE AVG_TIMER_WAIT > 1000000000000
                ORDER BY AVG_TIMER_WAIT DESC
                LIMIT ?
            ", [$limit]);

            return [
                'enabled' => true,
                'queries' => $query->getResultArray()
            ];
        } catch (\Exception $e) {
            return [
                'enabled' => true,
                'queries' => [],
                'message' => 'Performance schema not available'
            ];
        }
    }

    /**
     * Get database variables
     */
    public function getVariables(array $variables = []): array
    {
        if (empty($variables)) {
            $variables = [
                'version',
                'max_connections',
                'table_open_cache',
                'innodb_buffer_pool_size',
                'query_cache_size',
                'tmp_table_size',
                'max_heap_table_size'
            ];
        }

        $result = [];
        foreach ($variables as $var) {
            $query = $this->db->query("SHOW VARIABLES LIKE ?", [$var]);
            $row = $query->getRowArray();
            if ($row) {
                $result[$var] = $row['Value'];
            }
        }

        return $result;
    }

    /**
     * Get database status
     */
    public function getStatus(): array
    {
        $size = $this->getDatabaseSize();
        $connections = $this->getConnections();
        $variables = $this->getVariables();

        return [
            'database' => $size,
            'connections' => $connections,
            'variables' => $variables,
            'uptime' => $this->getUptime(),
        ];
    }

    /**
     * Get MySQL uptime
     */
    public function getUptime(): array
    {
        $query = $this->db->query("SHOW STATUS LIKE 'Uptime'");
        $result = $query->getRowArray();
        $seconds = (int)($result['Value'] ?? 0);

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

    /**
     * Kill a specific process
     */
    public function killProcess(int $processId): array
    {
        try {
            $this->db->query("KILL ?", [$processId]);
            return ['success' => true, 'message' => "Process {$processId} killed successfully"];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
