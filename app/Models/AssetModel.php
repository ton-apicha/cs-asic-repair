<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;

class AssetModel extends Model
{
    use AuditTrait;

    protected $table            = 'assets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id',
        'brand_model',
        'serial_number',
        'mac_address',
        'hash_rate',
        'external_condition',
        'status',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'customer_id'   => 'required|is_natural_no_zero',
        'brand_model'   => 'required|max_length[255]',
        'serial_number' => 'required|max_length[100]|is_unique[assets.serial_number,id,{id}]',
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
     * Search assets by serial number
     */
    public function search(string $term, int $limit = 10): array
    {
        return $this->select('assets.*, customers.name as customer_name, customers.phone as customer_phone')
            ->join('customers', 'customers.id = assets.customer_id')
            ->like('assets.serial_number', $term)
            ->orLike('assets.brand_model', $term)
            ->limit($limit)
            ->findAll();
    }

    /**
     * Find asset by serial number
     */
    public function findBySerialNumber(string $serialNumber): ?array
    {
        return $this->where('serial_number', $serialNumber)->first();
    }

    /**
     * Get asset with customer and job history
     */
    public function getWithHistory(int $id): ?array
    {
        $asset = $this->select('assets.*, customers.name as customer_name, customers.phone as customer_phone')
            ->join('customers', 'customers.id = assets.customer_id')
            ->where('assets.id', $id)
            ->first();
        
        if (!$asset) {
            return null;
        }

        // Get job history
        $jobModel = new JobCardModel();
        $asset['jobs'] = $jobModel->where('asset_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $asset;
    }

    /**
     * Get assets by status
     */
    public function getByStatus(string $status): array
    {
        return $this->select('assets.*, customers.name as customer_name')
            ->join('customers', 'customers.id = assets.customer_id')
            ->where('assets.status', $status)
            ->findAll();
    }

    /**
     * Get assets for a customer
     */
    public function getByCustomer(int $customerId): array
    {
        return $this->where('customer_id', $customerId)->findAll();
    }

    /**
     * Update asset status based on job status
     */
    public function updateStatusFromJob(int $assetId, string $jobStatus): bool
    {
        $statusMap = [
            'new_checkin'    => 'repairing',
            'pending_repair' => 'repairing',
            'in_progress'    => 'repairing',
            'repair_done'    => 'repaired',
            'ready_handover' => 'repaired',
            'delivered'      => 'returned',
            'cancelled'      => 'stored',
        ];

        $newStatus = $statusMap[$jobStatus] ?? 'stored';
        
        return $this->update($assetId, ['status' => $newStatus]);
    }

    /**
     * Get or create asset by serial number
     * Used when creating a job - auto-creates asset if S/N doesn't exist
     */
    public function getOrCreate(array $data): array
    {
        $existing = $this->findBySerialNumber($data['serial_number']);
        
        if ($existing) {
            // Update existing asset with new data (except S/N)
            unset($data['serial_number']);
            $this->update($existing['id'], $data);
            return $this->find($existing['id']);
        }

        // Create new asset
        $this->insert($data);
        return $this->find($this->getInsertID());
    }
}

