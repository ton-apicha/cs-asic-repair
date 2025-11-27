<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;

class JobCardModel extends Model
{
    use AuditTrait;

    protected $table            = 'job_cards';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_id',
        'customer_id',
        'asset_id',
        'branch_id',
        'technician_id',
        'symptom',
        'diagnosis',
        'solution',
        'notes',
        'status',
        'is_warranty_claim',
        'original_job_id',
        'labor_cost',
        'parts_cost',
        'total_cost',
        'vat_amount',
        'grand_total',
        'amount_paid',
        'checkin_date',
        'repair_start_date',
        'repair_end_date',
        'delivery_date',
        'cancel_reason',
        'is_locked',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Status constants
    const STATUS_NEW_CHECKIN    = 'new_checkin';
    const STATUS_PENDING_REPAIR = 'pending_repair';
    const STATUS_IN_PROGRESS    = 'in_progress';
    const STATUS_REPAIR_DONE    = 'repair_done';
    const STATUS_READY_HANDOVER = 'ready_handover';
    const STATUS_DELIVERED      = 'delivered';
    const STATUS_CANCELLED      = 'cancelled';

    // Callbacks
    protected $beforeInsert = ['generateJobId', 'setCheckinDate'];
    protected $afterInsert  = ['auditInsert', 'updateSymptomHistory', 'updateAssetStatus'];
    protected $afterUpdate  = ['auditUpdate', 'handleStatusChange'];
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
     * Generate Job ID in format YYMMDDXXX
     */
    protected function generateJobId(array $data): array
    {
        if (empty($data['data']['job_id'])) {
            $data['data']['job_id'] = $this->getNextJobId();
        }
        return $data;
    }

    /**
     * Set checkin date
     */
    protected function setCheckinDate(array $data): array
    {
        if (empty($data['data']['checkin_date'])) {
            $data['data']['checkin_date'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Update symptom history after job creation
     */
    protected function updateSymptomHistory(array $data): array
    {
        if ($data['result'] && !empty($data['data']['symptom'])) {
            $symptomModel = new SymptomHistoryModel();
            $symptomModel->recordSymptom($data['data']['symptom']);
        }
        return $data;
    }

    /**
     * Update asset status after job creation
     */
    protected function updateAssetStatus(array $data): array
    {
        if ($data['result'] && !empty($data['data']['asset_id'])) {
            $assetModel = new AssetModel();
            $assetModel->updateStatusFromJob($data['data']['asset_id'], 'new_checkin');
        }
        return $data;
    }

    /**
     * Handle status change callbacks
     */
    protected function handleStatusChange(array $data): array
    {
        if (!$data['result'] || !isset($data['data']['status'])) {
            return $data;
        }

        $jobId = $data['id'][0] ?? $data['id'];
        $job = $this->find($jobId);
        
        if (!$job) {
            return $data;
        }

        // Update timestamps based on status
        $updates = [];
        switch ($data['data']['status']) {
            case self::STATUS_IN_PROGRESS:
                if (empty($job['repair_start_date'])) {
                    $updates['repair_start_date'] = date('Y-m-d H:i:s');
                }
                break;
            case self::STATUS_REPAIR_DONE:
                $updates['repair_end_date'] = date('Y-m-d H:i:s');
                break;
            case self::STATUS_DELIVERED:
                $updates['delivery_date'] = date('Y-m-d H:i:s');
                $updates['is_locked'] = 1;
                // Deduct stock
                $this->deductPartsStock($jobId);
                break;
        }

        if (!empty($updates)) {
            $this->withoutAudit()->update($jobId, $updates);
        }

        // Update asset status
        if (!empty($job['asset_id'])) {
            $assetModel = new AssetModel();
            $assetModel->updateStatusFromJob($job['asset_id'], $data['data']['status']);
        }

        return $data;
    }

    /**
     * Get next Job ID
     */
    public function getNextJobId(): string
    {
        $db = \Config\Database::connect();
        $yearMonth = date('ym');
        
        // Get or create counter for this month
        $counter = $db->table('job_id_counter')
            ->where('year_month', $yearMonth)
            ->get()
            ->getRowArray();

        if ($counter) {
            $nextNumber = $counter['last_number'] + 1;
            $db->table('job_id_counter')
                ->where('year_month', $yearMonth)
                ->update([
                    'last_number' => $nextNumber,
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
        } else {
            $nextNumber = 1;
            $db->table('job_id_counter')->insert([
                'year_month'  => $yearMonth,
                'last_number' => $nextNumber,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        // Format: YYMMDDXXX (e.g., 251127001)
        $day = date('d');
        return $yearMonth . $day . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get job with all related data
     */
    public function getWithDetails(int $id): ?array
    {
        $job = $this->select('job_cards.*, 
                customers.name as customer_name, customers.phone as customer_phone,
                assets.brand_model, assets.serial_number,
                branches.name as branch_name,
                users.name as technician_name,
                creator.name as created_by_name')
            ->join('customers', 'customers.id = job_cards.customer_id')
            ->join('assets', 'assets.id = job_cards.asset_id')
            ->join('branches', 'branches.id = job_cards.branch_id')
            ->join('users', 'users.id = job_cards.technician_id', 'left')
            ->join('users as creator', 'creator.id = job_cards.created_by', 'left')
            ->where('job_cards.id', $id)
            ->first();

        if (!$job) {
            return null;
        }

        // Get parts used
        $jobPartsModel = new JobPartsModel();
        $job['parts'] = $jobPartsModel->getByJob($id);

        // Get payments
        $paymentModel = new PaymentModel();
        $job['payments'] = $paymentModel->getByJob($id);

        // Get original job if this is a warranty claim
        if ($job['is_warranty_claim'] && $job['original_job_id']) {
            $job['original_job'] = $this->find($job['original_job_id']);
        }

        return $job;
    }

    /**
     * Get jobs by status
     */
    public function getByStatus(string $status, ?int $branchId = null): array
    {
        $builder = $this->select('job_cards.*, 
                customers.name as customer_name,
                assets.brand_model, assets.serial_number')
            ->join('customers', 'customers.id = job_cards.customer_id')
            ->join('assets', 'assets.id = job_cards.asset_id')
            ->where('job_cards.status', $status);

        if ($branchId) {
            $builder->where('job_cards.branch_id', $branchId);
        }

        return $builder->orderBy('job_cards.created_at', 'DESC')->findAll();
    }

    /**
     * Get jobs grouped by status for Kanban board
     */
    public function getGroupedByStatus(?int $branchId = null): array
    {
        $statuses = [
            self::STATUS_NEW_CHECKIN,
            self::STATUS_PENDING_REPAIR,
            self::STATUS_IN_PROGRESS,
            self::STATUS_REPAIR_DONE,
            self::STATUS_READY_HANDOVER,
        ];

        $result = [];
        foreach ($statuses as $status) {
            $result[$status] = $this->getByStatus($status, $branchId);
        }

        return $result;
    }

    /**
     * Search jobs
     */
    public function search(string $term): array
    {
        return $this->select('job_cards.*, 
                customers.name as customer_name,
                assets.serial_number')
            ->join('customers', 'customers.id = job_cards.customer_id')
            ->join('assets', 'assets.id = job_cards.asset_id')
            ->like('job_cards.job_id', $term)
            ->orLike('customers.name', $term)
            ->orLike('assets.serial_number', $term)
            ->orderBy('job_cards.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Deduct parts stock when job is delivered
     */
    protected function deductPartsStock(int $jobId): void
    {
        $jobPartsModel = new JobPartsModel();
        $inventoryModel = new PartsInventoryModel();

        $parts = $jobPartsModel->where('job_card_id', $jobId)
            ->where('is_deducted', 0)
            ->findAll();

        foreach ($parts as $part) {
            $inventoryModel->deductStock(
                $part['part_id'],
                $part['quantity'],
                $jobId,
                session()->get('userId')
            );

            $jobPartsModel->update($part['id'], ['is_deducted' => 1]);
        }
    }

    /**
     * Calculate totals for a job
     */
    public function calculateTotals(int $jobId): array
    {
        $job = $this->find($jobId);
        if (!$job) {
            return [];
        }

        // Get parts cost
        $jobPartsModel = new JobPartsModel();
        $partsCost = $jobPartsModel->getTotalCostForJob($jobId);

        // Get settings for VAT
        $settingModel = new SettingModel();
        $vatType = $settingModel->get('vat_type');
        $vatRate = (float) $settingModel->get('vat_rate') / 100;

        $laborCost = (float) $job['labor_cost'];
        $totalCost = $laborCost + $partsCost;

        if ($vatType === 'inclusive') {
            $vatAmount = $totalCost * $vatRate;
            $grandTotal = $totalCost + $vatAmount;
        } else {
            $vatAmount = 0;
            $grandTotal = $totalCost;
        }

        $this->withoutAudit()->update($jobId, [
            'parts_cost'  => $partsCost,
            'total_cost'  => $totalCost,
            'vat_amount'  => $vatAmount,
            'grand_total' => $grandTotal,
        ]);

        return [
            'labor_cost'  => $laborCost,
            'parts_cost'  => $partsCost,
            'total_cost'  => $totalCost,
            'vat_amount'  => $vatAmount,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     * Get warranty claims for a job
     */
    public function getWarrantyClaims(int $originalJobId): array
    {
        return $this->where('original_job_id', $originalJobId)
            ->where('is_warranty_claim', 1)
            ->findAll();
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats(?int $branchId = null): array
    {
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');

        $builder = $this->builder();

        // Today's jobs
        $todayJobs = (clone $builder)->where('DATE(checkin_date)', $today);
        if ($branchId) $todayJobs->where('branch_id', $branchId);
        $todayCount = $todayJobs->countAllResults();

        // Monthly jobs
        $monthlyJobs = (clone $builder)->where('checkin_date >=', $monthStart);
        if ($branchId) $monthlyJobs->where('branch_id', $branchId);
        $monthlyCount = $monthlyJobs->countAllResults();

        // Pending jobs (not delivered/cancelled)
        $pendingStatuses = [
            self::STATUS_NEW_CHECKIN,
            self::STATUS_PENDING_REPAIR,
            self::STATUS_IN_PROGRESS,
            self::STATUS_REPAIR_DONE,
            self::STATUS_READY_HANDOVER,
        ];
        $pendingJobs = (clone $builder)->whereIn('status', $pendingStatuses);
        if ($branchId) $pendingJobs->where('branch_id', $branchId);
        $pendingCount = $pendingJobs->countAllResults();

        // Completed this month
        $completedJobs = (clone $builder)
            ->where('status', self::STATUS_DELIVERED)
            ->where('delivery_date >=', $monthStart);
        if ($branchId) $completedJobs->where('branch_id', $branchId);
        $completedCount = $completedJobs->countAllResults();

        return [
            'today'     => $todayCount,
            'monthly'   => $monthlyCount,
            'pending'   => $pendingCount,
            'completed' => $completedCount,
        ];
    }
}

