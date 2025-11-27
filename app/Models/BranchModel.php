<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;

class BranchModel extends Model
{
    use AuditTrait;

    protected $table            = 'branches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'address',
        'phone',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
    ];

    // Callbacks
    protected $afterInsert  = ['auditInsert'];
    protected $afterUpdate  = ['auditUpdate'];
    protected $beforeDelete = ['auditDelete'];

    protected function auditInsert(array $data): array
    {
        if ($data['result']) {
            $this->logCreate($data['data'], $data['id']);
        }
        return $data;
    }

    protected function auditUpdate(array $data): array
    {
        if ($data['result'] && isset($data['id'])) {
            $oldData = $this->find($data['id'][0] ?? $data['id']);
            if ($oldData) {
                $this->logUpdate($data['id'][0] ?? $data['id'], $oldData, $data['data']);
            }
        }
        return $data;
    }

    protected function auditDelete(array $data): array
    {
        if (isset($data['id'])) {
            $oldData = $this->find($data['id'][0] ?? $data['id']);
            if ($oldData) {
                $this->logDelete($data['id'][0] ?? $data['id'], $oldData);
            }
        }
        return $data;
    }

    /**
     * Get active branches
     */
    public function getActive(): array
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Get branch dropdown options
     */
    public function getDropdown(): array
    {
        $branches = $this->getActive();
        $options = [];
        foreach ($branches as $branch) {
            $options[$branch['id']] = $branch['name'];
        }
        return $options;
    }
}

