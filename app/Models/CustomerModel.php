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
        'name',
        'phone',
        'email',
        'address',
        'tax_id',
        'notes',
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
     */
    public function search(string $term, int $limit = 10): array
    {
        return $this->like('name', $term)
            ->orLike('phone', $term)
            ->limit($limit)
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
}

