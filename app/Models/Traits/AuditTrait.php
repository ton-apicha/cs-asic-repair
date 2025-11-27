<?php

namespace App\Models\Traits;

use App\Models\AuditLogModel;

/**
 * Audit Trail Trait
 * 
 * Provides automatic logging of CRUD operations for models.
 * Include this trait in any model that requires audit logging.
 * 
 * Models using this trait can define:
 * - protected $auditEnabled = true/false (default: true)
 * - protected $auditExclude = ['field1', 'field2'] (default: ['password', 'updated_at', 'created_at'])
 */
trait AuditTrait
{
    /**
     * Get audit enabled status
     */
    protected function isAuditEnabled(): bool
    {
        return $this->auditEnabled ?? true;
    }

    /**
     * Get excluded fields for audit
     */
    protected function getAuditExcludeFields(): array
    {
        return $this->auditExclude ?? ['password', 'updated_at', 'created_at'];
    }

    /**
     * Log a create action
     *
     * @param array $data The data that was inserted
     * @param int   $id   The ID of the new record
     */
    protected function logCreate(array $data, int $id): void
    {
        if (!$this->isAuditEnabled()) {
            return;
        }

        $filteredData = $this->filterAuditData($data);

        $this->createAuditLog('CREATE', $id, null, $filteredData);
    }

    /**
     * Log an update action
     *
     * @param int   $id      The ID of the record
     * @param array $oldData The old data before update
     * @param array $newData The new data after update
     */
    protected function logUpdate(int $id, array $oldData, array $newData): void
    {
        if (!$this->isAuditEnabled()) {
            return;
        }

        $filteredOld = $this->filterAuditData($oldData);
        $filteredNew = $this->filterAuditData($newData);

        // Only log if there are actual changes
        $changes = $this->getChanges($filteredOld, $filteredNew);
        
        if (empty($changes['old']) && empty($changes['new'])) {
            return;
        }

        $this->createAuditLog('UPDATE', $id, $changes['old'], $changes['new']);
    }

    /**
     * Log a delete action
     *
     * @param int   $id   The ID of the record
     * @param array $data The data that was deleted
     */
    protected function logDelete(int $id, array $data): void
    {
        if (!$this->isAuditEnabled()) {
            return;
        }

        $filteredData = $this->filterAuditData($data);

        $this->createAuditLog('DELETE', $id, $filteredData, null);
    }

    /**
     * Create audit log entry
     *
     * @param string     $action
     * @param int        $recordId
     * @param array|null $oldValues
     * @param array|null $newValues
     */
    private function createAuditLog(string $action, int $recordId, ?array $oldValues, ?array $newValues): void
    {
        $auditModel = new AuditLogModel();
        
        $session = session();
        $request = service('request');

        $auditModel->insert([
            'user_id'    => $session->get('userId'),
            'action'     => $action,
            'table_name' => $this->table,
            'record_id'  => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
            'new_values' => $newValues ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Filter out excluded fields from audit data
     *
     * @param array $data
     * @return array
     */
    private function filterAuditData(array $data): array
    {
        return array_diff_key($data, array_flip($this->getAuditExcludeFields()));
    }

    /**
     * Get only the changed fields between old and new data
     *
     * @param array $oldData
     * @param array $newData
     * @return array ['old' => [...], 'new' => [...]]
     */
    private function getChanges(array $oldData, array $newData): array
    {
        $changedOld = [];
        $changedNew = [];

        foreach ($newData as $key => $value) {
            if (!isset($oldData[$key]) || $oldData[$key] !== $value) {
                $changedOld[$key] = $oldData[$key] ?? null;
                $changedNew[$key] = $value;
            }
        }

        return [
            'old' => $changedOld,
            'new' => $changedNew,
        ];
    }

    /**
     * Disable audit logging temporarily
     *
     * @return $this
     */
    public function withoutAudit(): self
    {
        $this->auditEnabled = false;
        return $this;
    }

    /**
     * Enable audit logging
     *
     * @return $this
     */
    public function withAudit(): self
    {
        $this->auditEnabled = true;
        return $this;
    }
}
