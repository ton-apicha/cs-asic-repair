<?php

namespace App\Models;

use CodeIgniter\Model;

class StockTransactionModel extends Model
{
    protected $table            = 'stock_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'part_id',
        'job_card_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_cost',
        'reference',
        'notes',
        'user_id',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * Get transactions for a part
     */
    public function getByPart(int $partId, int $limit = 50): array
    {
        return $this->select('stock_transactions.*, users.name as user_name, job_cards.job_id')
            ->join('users', 'users.id = stock_transactions.user_id', 'left')
            ->join('job_cards', 'job_cards.id = stock_transactions.job_card_id', 'left')
            ->where('part_id', $partId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get transactions for a job
     */
    public function getByJob(int $jobId): array
    {
        return $this->select('stock_transactions.*, parts_inventory.name as part_name, parts_inventory.part_code')
            ->join('parts_inventory', 'parts_inventory.id = stock_transactions.part_id')
            ->where('job_card_id', $jobId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get transactions by date range
     */
    public function getByDateRange(string $startDate, string $endDate, ?string $type = null): array
    {
        $builder = $this->select('stock_transactions.*, parts_inventory.name as part_name, parts_inventory.part_code, users.name as user_name')
            ->join('parts_inventory', 'parts_inventory.id = stock_transactions.part_id')
            ->join('users', 'users.id = stock_transactions.user_id', 'left')
            ->where('stock_transactions.created_at >=', $startDate)
            ->where('stock_transactions.created_at <=', $endDate);

        if ($type) {
            $builder->where('type', $type);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Get total cost of parts used in a date range
     */
    public function getTotalCostUsed(string $startDate, string $endDate, ?int $branchId = null): float
    {
        $builder = $this->select('SUM(ABS(stock_transactions.quantity) * stock_transactions.unit_cost) as total_cost')
            ->where('type', 'out')
            ->where('stock_transactions.created_at >=', $startDate)
            ->where('stock_transactions.created_at <=', $endDate);

        if ($branchId) {
            $builder->join('job_cards', 'job_cards.id = stock_transactions.job_card_id')
                ->where('job_cards.branch_id', $branchId);
        }

        $result = $builder->first();
        return (float) ($result['total_cost'] ?? 0);
    }
}

