<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;

class CustomerModel extends Model
{
    use AuditTrait;

    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'branch_id',
        'name',
        'phone',
        'email',
        'address',
        'tax_id',
        'notes',
        'credit_limit',
        'credit_terms',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'  => 'required|min_length[2]|max_length[255]',
        'phone' => 'required|min_length[9]|max_length[20]',
        'email' => 'permit_empty|valid_email',
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
     * Search customers by name or phone
     * 
     * @param string $term Search term
     * @param int $limit Result limit
     * @param int|null $branchId Filter by branch (null = all branches for Super Admin)
     */
    public function search(string $term, int $limit = 10, ?int $branchId = null): array
    {
        $builder = $this->groupStart()
            ->like('name', $term)
            ->orLike('phone', $term)
            ->groupEnd();
        
        // Apply branch filter
        if ($branchId !== null) {
            $builder->groupStart()
                ->where('branch_id', $branchId)
                ->orWhere('branch_id', null) // Include customers with no branch (Super Admin created)
                ->groupEnd();
        }
        
        return $builder->limit($limit)->findAll();
    }

    /**
     * Get customers by branch
     * 
     * @param int|null $branchId Filter by branch (null = all branches)
     */
    public function getByBranch(?int $branchId = null): array
    {
        if ($branchId === null) {
            return $this->findAll();
        }
        
        return $this->groupStart()
            ->where('branch_id', $branchId)
            ->orWhere('branch_id', null) // Include global customers
            ->groupEnd()
            ->findAll();
    }

    /**
     * Get all customers with branch filter applied
     */
    public function getAllWithBranchFilter(?int $branchId = null): array
    {
        if ($branchId === null) {
            return $this->orderBy('name', 'ASC')->findAll();
        }
        
        return $this->groupStart()
            ->where('branch_id', $branchId)
            ->orWhere('branch_id', null)
            ->groupEnd()
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Get customer with their assets and jobs
     */
    public function getWithHistory(int $id): ?array
    {
        $customer = $this->find($id);
        
        if (!$customer) {
            return null;
        }

        // Get assets
        $assetModel = new AssetModel();
        $customer['assets'] = $assetModel->where('customer_id', $id)->findAll();

        // Get jobs
        $jobModel = new JobCardModel();
        $customer['jobs'] = $jobModel->where('customer_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $customer;
    }

    /**
     * Find customer by phone
     */
    public function findByPhone(string $phone): ?array
    {
        return $this->where('phone', $phone)->first();
    }

    /**
     * Get available credit for a customer
     */
    public function getAvailableCredit(int $customerId): float
    {
        $customer = $this->find($customerId);
        if (!$customer) {
            return 0;
        }
        
        return max(0, ($customer['credit_limit'] ?? 0) - ($customer['credit_used'] ?? 0));
    }

    /**
     * Use credit (increase credit_used)
     * Separate method to prevent mass assignment
     */
    public function useCredit(int $customerId, float $amount): bool
    {
        $customer = $this->find($customerId);
        if (!$customer) {
            return false;
        }
        
        $newCreditUsed = ($customer['credit_used'] ?? 0) + $amount;
        
        return $this->allowedFields(['credit_used'])
            ->update($customerId, ['credit_used' => $newCreditUsed]);
    }

    /**
     * Pay credit (decrease credit_used)
     * Separate method to prevent mass assignment
     */
    public function payCredit(int $customerId, float $amount): bool
    {
        $customer = $this->find($customerId);
        if (!$customer) {
            return false;
        }
        
        $newCreditUsed = max(0, ($customer['credit_used'] ?? 0) - $amount);
        
        return $this->allowedFields(['credit_used'])
            ->update($customerId, ['credit_used' => $newCreditUsed]);
    }

    /**
     * Get customers with outstanding balance
     */
    public function getWithOutstandingBalance(): array
    {
        return $this->where('credit_used >', 0)
            ->orderBy('credit_used', 'DESC')
            ->findAll();
    }

    /**
     * Get overdue customers (past credit terms)
     */
    public function getOverdueCustomers(): array
    {
        // This would require joining with job/payment dates
        // For now, just return customers with outstanding balance
        return $this->getWithOutstandingBalance();
    }
}

