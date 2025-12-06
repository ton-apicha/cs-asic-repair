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

    // ========================================================================
    // API Endpoints for Charts (AJAX)
    // ========================================================================

    /**
     * Get revenue data for charts (AJAX)
     */
    public function revenueApi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $period = $this->request->getGet('period') ?? 'weekly';
        $paymentModel = new PaymentModel();

        $labels = [];
        $values = [];

        switch ($period) {
            case 'daily':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-{$i} days"));
                    $labels[] = date('d M', strtotime($date));
                    $values[] = (float) $paymentModel->getDailyRevenue($date);
                }
                break;

            case 'weekly':
                // Last 8 weeks
                for ($i = 7; $i >= 0; $i--) {
                    $weekStart = date('Y-m-d', strtotime("-{$i} weeks monday"));
                    $weekEnd = date('Y-m-d', strtotime("-{$i} weeks sunday"));
                    $labels[] = 'W' . date('W', strtotime($weekStart));

                    $payments = $paymentModel->getByDateRange(
                        $weekStart . ' 00:00:00',
                        $weekEnd . ' 23:59:59'
                    );
                    $values[] = array_sum(array_column($payments, 'amount'));
                }
                break;

            case 'monthly':
                // Last 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $year = (int) date('Y', strtotime("-{$i} months"));
                    $month = (int) date('m', strtotime("-{$i} months"));
                    $labels[] = date('M Y', strtotime("-{$i} months"));
                    $values[] = (float) $paymentModel->getMonthlyRevenue($year, $month);
                }
                break;

            default:
                // Default: last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-{$i} days"));
                    $labels[] = date('d M', strtotime($date));
                    $values[] = (float) $paymentModel->getDailyRevenue($date);
                }
        }

        return $this->response->setJSON([
            'success' => true,
            'labels' => $labels,
            'values' => $values,
            'period' => $period
        ]);
    }

    /**
     * Get job status distribution for charts (AJAX)
     */
    public function jobStatusApi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $jobModel = new JobCardModel();
        $stats = $jobModel->getStats();

        $statusLabels = [
            'new_checkin' => 'รับเครื่องใหม่',
            'pending_repair' => 'รอซ่อม',
            'in_progress' => 'กำลังซ่อม',
            'repair_done' => 'ซ่อมเสร็จ',
            'ready_handover' => 'พร้อมส่งมอบ',
            'delivered' => 'ส่งมอบแล้ว',
            'cancelled' => 'ยกเลิก'
        ];

        $labels = [];
        $values = [];
        $colors = [
            '#6366f1', // new_checkin
            '#f59e0b', // pending
            '#3b82f6', // in_progress
            '#10b981', // repair_done
            '#06b6d4', // ready_handover  
            '#8b5cf6', // delivered
            '#ef4444'  // cancelled
        ];

        foreach ($stats['by_status'] as $status) {
            $labels[] = $statusLabels[$status['status']] ?? $status['status'];
            $values[] = (int) $status['count'];
        }

        return $this->response->setJSON([
            'success' => true,
            'labels' => $labels,
            'data' => $values,
            'colors' => array_slice($colors, 0, count($values))
        ]);
    }

    /**
     * Get job trend data for charts (AJAX)
     */
    public function jobTrendApi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $period = $this->request->getGet('period') ?? '30d';
        $jobModel = new JobCardModel();

        $days = match ($period) {
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            default => 30,
        };

        $labels = [];
        $newJobs = [];
        $completedJobs = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));

            if ($days > 30) {
                // Show only every 3rd day for longer periods
                if ($i % 3 !== 0 && $i !== 0) continue;
            }

            $labels[] = date('d/m', strtotime($date));

            // Count new jobs on this date
            $newCount = $jobModel
                ->where('DATE(checkin_date)', $date)
                ->countAllResults(false);
            $newJobs[] = $newCount;

            // Count completed jobs on this date
            $completedCount = $jobModel
                ->where('DATE(delivery_date)', $date)
                ->where('status', 'delivered')
                ->countAllResults(false);
            $completedJobs[] = $completedCount;
        }

        return $this->response->setJSON([
            'success' => true,
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'งานใหม่',
                    'data' => $newJobs,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)'
                ],
                [
                    'label' => 'ส่งมอบแล้ว',
                    'data' => $completedJobs,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)'
                ]
            ],
            'period' => $period
        ]);
    }

    /**
     * Get top parts usage for charts (AJAX)
     */
    public function topPartsApi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $limit = min((int) ($this->request->getGet('limit') ?? 10), 20);

        $db = \Config\Database::connect();

        $parts = $db->table('job_parts')
            ->select('parts_inventory.name, SUM(job_parts.quantity) as total_used')
            ->join('parts_inventory', 'parts_inventory.id = job_parts.part_id')
            ->where('job_parts.created_at >=', date('Y-m-01'))
            ->groupBy('job_parts.part_id')
            ->orderBy('total_used', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $labels = array_column($parts, 'name');
        $values = array_map('intval', array_column($parts, 'total_used'));

        return $this->response->setJSON([
            'success' => true,
            'labels' => $labels,
            'values' => $values
        ]);
    }

    /**
     * Get technician performance for charts (AJAX)
     */
    public function technicianPerformanceApi()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        // Get jobs completed by each technician this month
        $performance = $db->table('job_cards')
            ->select('users.name as technician_name, COUNT(*) as jobs_completed')
            ->join('users', 'users.id = job_cards.technician_id')
            ->where('job_cards.status', 'delivered')
            ->where('job_cards.delivery_date >=', date('Y-m-01'))
            ->groupBy('job_cards.technician_id')
            ->orderBy('jobs_completed', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $labels = array_column($performance, 'technician_name');
        $values = array_map('intval', array_column($performance, 'jobs_completed'));

        return $this->response->setJSON([
            'success' => true,
            'labels' => $labels,
            'values' => $values
        ]);
    }
}
