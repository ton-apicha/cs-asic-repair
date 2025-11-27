<?php

namespace App\Models;

use CodeIgniter\Model;

class JobPartsModel extends Model
{
    protected $table            = 'job_parts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_card_id',
        'part_id',
        'quantity',
        'unit_price',
        'total_price',
        'serial_number',
        'notes',
        'added_by',
        'is_deducted',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callbacks
    protected $beforeInsert = ['calculateTotal'];
    protected $afterInsert  = ['updateJobTotals'];
    protected $afterDelete  = ['updateJobTotals'];

    /**
     * Calculate total price before insert
     */
    protected function calculateTotal(array $data): array
    {
        if (isset($data['data']['quantity']) && isset($data['data']['unit_price'])) {
            $data['data']['total_price'] = $data['data']['quantity'] * $data['data']['unit_price'];
        }
        return $data;
    }

    /**
     * Update job totals after insert/delete
     */
    protected function updateJobTotals(array $data): array
    {
        $jobId = $data['data']['job_card_id'] ?? null;
        
        if ($jobId) {
            $jobModel = new JobCardModel();
            $jobModel->calculateTotals($jobId);
        }
        
        return $data;
    }

    /**
     * Get parts for a job with part details
     */
    public function getByJob(int $jobId): array
    {
        return $this->select('job_parts.*, parts_inventory.part_code, parts_inventory.name as part_name')
            ->join('parts_inventory', 'parts_inventory.id = job_parts.part_id')
            ->where('job_card_id', $jobId)
            ->findAll();
    }

    /**
     * Get total cost for a job
     */
    public function getTotalCostForJob(int $jobId): float
    {
        $result = $this->selectSum('total_price')
            ->where('job_card_id', $jobId)
            ->first();
        
        return (float) ($result['total_price'] ?? 0);
    }

    /**
     * Add part to job
     */
    public function addPartToJob(int $jobId, int $partId, int $quantity, ?string $serialNumber = null, ?int $userId = null): bool
    {
        $inventoryModel = new PartsInventoryModel();
        $part = $inventoryModel->find($partId);
        
        if (!$part) {
            return false;
        }

        // Check if part already exists in this job
        $existing = $this->where('job_card_id', $jobId)
            ->where('part_id', $partId)
            ->first();

        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            return $this->update($existing['id'], [
                'quantity'    => $newQuantity,
                'total_price' => $newQuantity * $existing['unit_price'],
            ]);
        }

        // Insert new
        return $this->insert([
            'job_card_id'   => $jobId,
            'part_id'       => $partId,
            'quantity'      => $quantity,
            'unit_price'    => $part['sell_price'],
            'total_price'   => $quantity * $part['sell_price'],
            'serial_number' => $serialNumber,
            'added_by'      => $userId,
        ]);
    }

    /**
     * Remove part from job
     */
    public function removePartFromJob(int $jobPartId): bool
    {
        $part = $this->find($jobPartId);
        if (!$part) {
            return false;
        }

        $jobId = $part['job_card_id'];
        $deleted = $this->delete($jobPartId);

        if ($deleted) {
            // Update job totals
            $jobModel = new JobCardModel();
            $jobModel->calculateTotals($jobId);
        }

        return $deleted;
    }
}

