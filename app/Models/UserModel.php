<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;

class UserModel extends Model
{
    use AuditTrait;

    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'branch_id',
        'username',
        'password',
        'name',
        'email',
        'phone',
        'last_login',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'name'     => 'required|min_length[2]|max_length[255]',
        'role'     => 'required|in_list[admin,technician,super_admin]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    // Audit exclude fields
    protected $auditExclude = ['password', 'updated_at', 'created_at', 'last_login'];

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    protected $afterInsert  = ['auditInsert'];
    protected $afterUpdate  = ['auditUpdate'];
    protected $beforeDelete = ['auditDelete'];

    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }

        return $data;
    }

    /**
     * Audit insert callback
     */
    protected function auditInsert(array $data): array
    {
        if ($data['result']) {
            $this->logCreate($data['data'], $data['id']);
        }
        return $data;
    }

    /**
     * Audit update callback
     */
    protected function auditUpdate(array $data): array
    {
        if ($data['result'] && isset($data['id'])) {
            // Get old data before it was updated
            $oldData = $this->find($data['id'][0] ?? $data['id']);
            if ($oldData) {
                $this->logUpdate($data['id'][0] ?? $data['id'], $oldData, $data['data']);
            }
        }
        return $data;
    }

    /**
     * Audit delete callback
     */
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
     * Get users by role
     */
    public function getByRole(string $role): array
    {
        return $this->where('role', $role)
            ->where('is_active', 1)
            ->findAll();
    }

    /**
     * Get technicians
     */
    public function getTechnicians(): array
    {
        return $this->getByRole('technician');
    }

    /**
     * Get admins
     */
    public function getAdmins(): array
    {
        return $this->getByRole('admin');
    }

    /**
     * Set user role (separate method to prevent mass assignment)
     */
    public function setRole(int $userId, string $role): bool
    {
        if (!in_array($role, ['admin', 'technician', 'super_admin'])) {
            return false;
        }
        
        return $this->allowedFields(['role'])
            ->update($userId, ['role' => $role]);
    }

    /**
     * Set user active status (separate method to prevent mass assignment)
     */
    public function setActiveStatus(int $userId, bool $isActive): bool
    {
        return $this->allowedFields(['is_active'])
            ->update($userId, ['is_active' => $isActive ? 1 : 0]);
    }

    /**
     * Create user with role (for admin use)
     */
    public function createUserWithRole(array $data, string $role, bool $isActive = true): int|false
    {
        // Insert base user data first
        $baseData = array_intersect_key($data, array_flip($this->allowedFields));
        $result = $this->insert($baseData);
        
        if ($result === false) {
            return false;
        }
        
        $userId = $this->getInsertID();
        
        // Set role and status separately
        $this->allowedFields(['role', 'is_active'])
            ->update($userId, [
                'role' => $role,
                'is_active' => $isActive ? 1 : 0
            ]);
        
        return $userId;
    }
}

