<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;

class PartsInventoryModel extends Model
{
    use AuditTrait;

    protected $table            = 'parts_inventory';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'branch_id',
        'part_code',
        'name',
        'description',
        'serial_number',
        'cost_price',
        'sell_price',
        'quantity',
        'reorder_point',
        'location',
        'category',
        'notes',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'part_code'  => 'required|max_length[50]',
        'name'       => 'required|max_length[255]',
        'cost_price' => 'required|numeric|greater_than_equal_to[0]',
        'sell_price' => 'required|numeric|greater_than_equal_to[0]',
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
     * Search parts by code or name
     * 
     * @param string $term Search term
     * @param int $limit Result limit
     * @param int|null $branchId Filter by branch (null = all branches for Super Admin)
     */
    public function search(string $term, int $limit = 10, ?int $branchId = null): array
    {
        $builder = $this->groupStart()
            ->like('part_code', $term)
            ->orLike('name', $term)
            ->groupEnd()
            ->where('is_active', 1);
        
        // Apply branch filter - show central warehouse (branch_id=null) + branch-specific
        if ($branchId !== null) {
            $builder->groupStart()
                ->where('branch_id', $branchId)
                ->orWhere('branch_id', null) // Include central warehouse
                ->groupEnd();
        }
        
        return $builder->limit($limit)->findAll();
    }

    /**
     * Get parts by branch
     * Shows both central warehouse items and branch-specific items
     * 
     * @param int|null $branchId Filter by branch (null = all branches)
     */
    public function getByBranch(?int $branchId = null): array
    {
        $builder = $this->where('is_active', 1);
        
        if ($branchId !== null) {
            $builder->groupStart()
                ->where('branch_id', $branchId)
                ->orWhere('branch_id', null) // Include central warehouse
                ->groupEnd();
        }
        
        return $builder->findAll();
    }

    /**
     * Get all inventory items with branch filter
     */
    public function getAllWithBranchFilter(?int $branchId = null): array
    {
        $builder = $this->where('is_active', 1);
        
        if ($branchId !== null) {
            $builder->groupStart()
                ->where('branch_id', $branchId)
                ->orWhere('branch_id', null)
                ->groupEnd();
        }
        
        return $builder->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get parts with low stock
     */
    public function getLowStock(?int $branchId = null): array
    {
        $builder = $this->where('quantity <= reorder_point')
            ->where('is_active', 1);

        if ($branchId) {
            $builder->groupStart()
                ->where('branch_id', $branchId)
                ->orWhere('branch_id', null)
                ->groupEnd();
        }

        return $builder->findAll();
    }

    /**
     * Get parts by category
     */
    public function getByCategory(string $category): array
    {
        return $this->where('category', $category)
            ->where('is_active', 1)
            ->findAll();
    }

    /**
     * Get unique categories
     */
    public function getCategories(): array
    {
        return $this->select('category')
            ->where('category IS NOT NULL')
            ->where('is_active', 1)
            ->distinct()
            ->findAll();
    }

    /**
     * Deduct stock when job is delivered
     */
    public function deductStock(int $partId, int $quantity, ?int $jobId = null, ?int $userId = null): bool
    {
        $part = $this->find($partId);
        if (!$part) {
            return false;
        }

        $newQuantity = $part['quantity'] - $quantity;
        
        // Log transaction
        $transactionModel = new StockTransactionModel();
        $transactionModel->insert([
            'part_id'         => $partId,
            'job_card_id'     => $jobId,
            'type'            => 'out',
            'quantity'        => -$quantity,
            'quantity_before' => $part['quantity'],
            'quantity_after'  => $newQuantity,
            'unit_cost'       => $part['cost_price'],
            'user_id'         => $userId,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        // Update stock
        return $this->update($partId, ['quantity' => $newQuantity]);
    }

    /**
     * Add stock (restock)
     */
    public function addStock(int $partId, int $quantity, ?string $reference = null, ?int $userId = null): bool
    {
        $part = $this->find($partId);
        if (!$part) {
            return false;
        }

        $newQuantity = $part['quantity'] + $quantity;
        
        // Log transaction
        $transactionModel = new StockTransactionModel();
        $transactionModel->insert([
            'part_id'         => $partId,
            'type'            => 'in',
            'quantity'        => $quantity,
            'quantity_before' => $part['quantity'],
            'quantity_after'  => $newQuantity,
            'unit_cost'       => $part['cost_price'],
            'reference'       => $reference,
            'user_id'         => $userId,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        // Update stock
        return $this->update($partId, ['quantity' => $newQuantity]);
    }

    /**
     * Adjust stock (correction)
     */
    public function adjustStock(int $partId, int $newQuantity, ?string $notes = null, ?int $userId = null): bool
    {
        $part = $this->find($partId);
        if (!$part) {
            return false;
        }

        $difference = $newQuantity - $part['quantity'];
        
        // Log transaction
        $transactionModel = new StockTransactionModel();
        $transactionModel->insert([
            'part_id'         => $partId,
            'type'            => 'adjustment',
            'quantity'        => $difference,
            'quantity_before' => $part['quantity'],
            'quantity_after'  => $newQuantity,
            'notes'           => $notes,
            'user_id'         => $userId,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        // Update stock
        return $this->update($partId, ['quantity' => $newQuantity]);
    }

    /**
     * Get total inventory value
     */
    public function getTotalValue(?int $branchId = null): float
    {
        $builder = $this->selectSum('(quantity * cost_price)', 'total_value')
            ->where('is_active', 1);

        if ($branchId) {
            $builder->groupStart()
                ->where('branch_id', $branchId)
                ->orWhere('branch_id', null)
                ->groupEnd();
        }

        $result = $builder->first();
        return (float) ($result['total_value'] ?? 0);
    }
}

