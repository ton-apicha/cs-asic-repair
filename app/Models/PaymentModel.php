<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;

class PaymentModel extends Model
{
    use AuditTrait;

    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'job_card_id',
        'payment_method',
        'amount',
        'reference_number',
        'payment_date',
        'notes',
        'received_by',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callbacks
    protected $afterInsert  = ['auditInsert', 'updateJobPayment'];
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
     * Update job amount_paid after payment insert
     */
    protected function updateJobPayment(array $data): array
    {
        if ($data['result'] && isset($data['data']['job_card_id'])) {
            $jobId = $data['data']['job_card_id'];
            $totalPaid = $this->getTotalPaidForJob($jobId);
            
            $jobModel = new JobCardModel();
            $jobModel->withoutAudit()->update($jobId, ['amount_paid' => $totalPaid]);
        }
        return $data;
    }

    /**
     * Get payments for a job
     */
    public function getByJob(int $jobId): array
    {
        return $this->select('payments.*, users.name as received_by_name')
            ->join('users', 'users.id = payments.received_by', 'left')
            ->where('job_card_id', $jobId)
            ->orderBy('payment_date', 'DESC')
            ->findAll();
    }

    /**
     * Get total paid for a job
     */
    public function getTotalPaidForJob(int $jobId): float
    {
        $result = $this->selectSum('amount')
            ->where('job_card_id', $jobId)
            ->first();
        
        return (float) ($result['amount'] ?? 0);
    }

    /**
     * Record a payment
     */
    public function recordPayment(int $jobId, string $method, float $amount, ?string $reference = null, ?int $userId = null): bool
    {
        return $this->insert([
            'job_card_id'      => $jobId,
            'payment_method'   => $method,
            'amount'           => $amount,
            'reference_number' => $reference,
            'payment_date'     => date('Y-m-d H:i:s'),
            'received_by'      => $userId,
        ]);
    }

    /**
     * Get payments by date range
     */
    public function getByDateRange(string $startDate, string $endDate, ?int $branchId = null): array
    {
        $builder = $this->select('payments.*, job_cards.job_id, job_cards.branch_id')
            ->join('job_cards', 'job_cards.id = payments.job_card_id')
            ->where('payments.payment_date >=', $startDate)
            ->where('payments.payment_date <=', $endDate);

        if ($branchId) {
            $builder->where('job_cards.branch_id', $branchId);
        }

        return $builder->orderBy('payment_date', 'DESC')->findAll();
    }

    /**
     * Get daily revenue
     */
    public function getDailyRevenue(string $date, ?int $branchId = null): float
    {
        $builder = $this->selectSum('payments.amount')
            ->join('job_cards', 'job_cards.id = payments.job_card_id')
            ->where('DATE(payments.payment_date)', $date);

        if ($branchId) {
            $builder->where('job_cards.branch_id', $branchId);
        }

        $result = $builder->first();
        return (float) ($result['amount'] ?? 0);
    }

    /**
     * Get monthly revenue
     */
    public function getMonthlyRevenue(int $year, int $month, ?int $branchId = null): float
    {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $builder = $this->selectSum('payments.amount')
            ->join('job_cards', 'job_cards.id = payments.job_card_id')
            ->where('payments.payment_date >=', $startDate . ' 00:00:00')
            ->where('payments.payment_date <=', $endDate . ' 23:59:59');

        if ($branchId) {
            $builder->where('job_cards.branch_id', $branchId);
        }

        $result = $builder->first();
        return (float) ($result['amount'] ?? 0);
    }

    /**
     * Get revenue by payment method
     */
    public function getRevenueByMethod(string $startDate, string $endDate, ?int $branchId = null): array
    {
        $builder = $this->select('payment_method, SUM(amount) as total')
            ->join('job_cards', 'job_cards.id = payments.job_card_id')
            ->where('payments.payment_date >=', $startDate)
            ->where('payments.payment_date <=', $endDate)
            ->groupBy('payment_method');

        if ($branchId) {
            $builder->where('job_cards.branch_id', $branchId);
        }

        return $builder->findAll();
    }
}

