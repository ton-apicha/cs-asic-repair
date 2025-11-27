<?php

namespace App\Controllers;

use App\Models\JobCardModel;
use App\Models\PaymentModel;
use App\Models\PartsInventoryModel;
use App\Models\StockTransactionModel;

/**
 * Report Controller
 * 
 * Generates various reports for admin.
 */
class ReportController extends BaseController
{
    /**
     * Reports dashboard
     */
    public function index(): string
    {
        return view('reports/index', $this->getViewData([
            'title' => lang('App.reports'),
        ]));
    }

    /**
     * Sales Report
     */
    public function sales(): string
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $branchId = $this->request->getGet('branch_id');

        $paymentModel = new PaymentModel();
        
        $payments = $paymentModel->getByDateRange(
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59',
            $branchId
        );

        $totalRevenue = array_sum(array_column($payments, 'amount'));
        $byMethod = $paymentModel->getRevenueByMethod(
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59',
            $branchId
        );

        return view('reports/sales', $this->getViewData([
            'title'        => lang('App.salesReport'),
            'payments'     => $payments,
            'totalRevenue' => $totalRevenue,
            'byMethod'     => $byMethod,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
        ]));
    }

    /**
     * Profit Report
     */
    public function profit(): string
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $branchId = $this->request->getGet('branch_id');

        $paymentModel = new PaymentModel();
        $transactionModel = new StockTransactionModel();

        // Total revenue
        $payments = $paymentModel->getByDateRange(
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59',
            $branchId
        );
        $totalRevenue = array_sum(array_column($payments, 'amount'));

        // Total cost of parts used
        $partsCost = $transactionModel->getTotalCostUsed(
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59',
            $branchId
        );

        $grossProfit = $totalRevenue - $partsCost;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        return view('reports/profit', $this->getViewData([
            'title'        => lang('App.profitReport'),
            'totalRevenue' => $totalRevenue,
            'partsCost'    => $partsCost,
            'grossProfit'  => $grossProfit,
            'profitMargin' => $profitMargin,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
        ]));
    }

    /**
     * Warranty Report
     */
    public function warranty(): string
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $jobModel = new JobCardModel();
        
        $claims = $jobModel->select('job_cards.*, customers.name as customer_name, assets.serial_number')
            ->join('customers', 'customers.id = job_cards.customer_id')
            ->join('assets', 'assets.id = job_cards.asset_id')
            ->where('is_warranty_claim', 1)
            ->where('job_cards.created_at >=', $startDate . ' 00:00:00')
            ->where('job_cards.created_at <=', $endDate . ' 23:59:59')
            ->findAll();

        $totalWarrantyCost = array_sum(array_column($claims, 'parts_cost'));
        $totalWarrantyCost += array_sum(array_column($claims, 'labor_cost'));

        // Calculate claim rate
        $totalJobs = $jobModel->where('created_at >=', $startDate . ' 00:00:00')
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->countAllResults();
        
        $claimRate = $totalJobs > 0 ? (count($claims) / $totalJobs) * 100 : 0;

        return view('reports/warranty', $this->getViewData([
            'title'             => lang('App.warrantyReport'),
            'claims'            => $claims,
            'totalWarrantyCost' => $totalWarrantyCost,
            'claimRate'         => $claimRate,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
        ]));
    }

    /**
     * Work in Progress Report
     */
    public function wip(): string
    {
        $jobModel = new JobCardModel();

        $pendingStatuses = [
            JobCardModel::STATUS_NEW_CHECKIN,
            JobCardModel::STATUS_PENDING_REPAIR,
            JobCardModel::STATUS_IN_PROGRESS,
            JobCardModel::STATUS_REPAIR_DONE,
            JobCardModel::STATUS_READY_HANDOVER,
        ];

        $jobs = $jobModel->select('job_cards.*, customers.name as customer_name, assets.serial_number, users.name as technician_name')
            ->join('customers', 'customers.id = job_cards.customer_id')
            ->join('assets', 'assets.id = job_cards.asset_id')
            ->join('users', 'users.id = job_cards.technician_id', 'left')
            ->whereIn('job_cards.status', $pendingStatuses)
            ->orderBy('job_cards.checkin_date', 'ASC')
            ->findAll();

        // Calculate aging
        foreach ($jobs as &$job) {
            $checkinDate = new \DateTime($job['checkin_date']);
            $now = new \DateTime();
            $job['days_pending'] = $checkinDate->diff($now)->days;
        }

        return view('reports/wip', $this->getViewData([
            'title' => lang('App.wipReport'),
            'jobs'  => $jobs,
        ]));
    }

    /**
     * Parts Usage Report
     */
    public function partsUsage(): string
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $db = \Config\Database::connect();

        $parts = $db->table('job_parts')
            ->select('parts_inventory.part_code, parts_inventory.name, SUM(job_parts.quantity) as total_used, SUM(job_parts.total_price) as total_value')
            ->join('parts_inventory', 'parts_inventory.id = job_parts.part_id')
            ->join('job_cards', 'job_cards.id = job_parts.job_card_id')
            ->where('job_cards.created_at >=', $startDate . ' 00:00:00')
            ->where('job_cards.created_at <=', $endDate . ' 23:59:59')
            ->groupBy('job_parts.part_id')
            ->orderBy('total_used', 'DESC')
            ->get()
            ->getResultArray();

        return view('reports/parts_usage', $this->getViewData([
            'title'     => 'Parts Usage Report',
            'parts'     => $parts,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]));
    }

    /**
     * KPI Dashboard
     */
    public function kpi(): string
    {
        $jobModel = new JobCardModel();
        $paymentModel = new PaymentModel();
        $inventoryModel = new PartsInventoryModel();

        // Date ranges
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');

        // Job statistics
        $jobStats = $jobModel->getStats();

        // Revenue
        $todayRevenue = $paymentModel->getDailyRevenue($today);
        $monthlyRevenue = $paymentModel->getMonthlyRevenue((int)date('Y'), (int)date('m'));

        // Average repair time (for completed jobs this month)
        $completedJobs = $jobModel->where('status', 'delivered')
            ->where('delivery_date >=', $monthStart)
            ->where('delivery_date <=', $monthEnd)
            ->where('repair_start_date IS NOT NULL')
            ->where('repair_end_date IS NOT NULL')
            ->findAll();

        $avgRepairTime = 0;
        if (!empty($completedJobs)) {
            $totalHours = 0;
            foreach ($completedJobs as $job) {
                $start = new \DateTime($job['repair_start_date']);
                $end = new \DateTime($job['repair_end_date']);
                $totalHours += $start->diff($end)->h + ($start->diff($end)->days * 24);
            }
            $avgRepairTime = $totalHours / count($completedJobs);
        }

        // Claim rate
        $totalCompleted = $jobModel->where('status', 'delivered')
            ->where('delivery_date >=', $monthStart)
            ->countAllResults();
        
        $totalClaims = $jobModel->where('is_warranty_claim', 1)
            ->where('created_at >=', $monthStart)
            ->countAllResults();
        
        $claimRate = $totalCompleted > 0 ? ($totalClaims / $totalCompleted) * 100 : 0;
        $ftfr = 100 - $claimRate; // First Time Fix Rate

        // Inventory value
        $inventoryValue = $inventoryModel->getTotalValue();

        return view('reports/kpi', $this->getViewData([
            'title'          => lang('App.kpiReport'),
            'jobStats'       => $jobStats,
            'todayRevenue'   => $todayRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'avgRepairTime'  => $avgRepairTime,
            'claimRate'      => $claimRate,
            'ftfr'           => $ftfr,
            'inventoryValue' => $inventoryValue,
        ]));
    }
}

