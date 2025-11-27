<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    /**
     * Get logs for a specific record
     *
     * @param string $tableName
     * @param int    $recordId
     * @param int    $limit
     * @return array
     */
    public function getLogsForRecord(string $tableName, int $recordId, int $limit = 50): array
    {
        return $this->select('audit_logs.*, users.name as user_name')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->where('table_name', $tableName)
            ->where('record_id', $recordId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get logs by user
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getLogsByUser(int $userId, int $limit = 100): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get recent logs
     *
     * @param int $limit
     * @return array
     */
    public function getRecentLogs(int $limit = 100): array
    {
        return $this->select('audit_logs.*, users.name as user_name')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get logs by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $tableName
     * @return array
     */
    public function getLogsByDateRange(string $startDate, string $endDate, ?string $tableName = null): array
    {
        $builder = $this->select('audit_logs.*, users.name as user_name')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->where('audit_logs.created_at >=', $startDate)
            ->where('audit_logs.created_at <=', $endDate);

        if ($tableName) {
            $builder->where('table_name', $tableName);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }
}

