<?php

namespace App\Controllers;

use App\Models\JobCardModel;
use App\Models\PartsInventoryModel;
use App\Models\PaymentModel;

/**
 * Dashboard Controller
 * 
 * Displays different dashboards based on user role.
 */
class DashboardController extends BaseController
{
    protected JobCardModel $jobModel;
    protected PartsInventoryModel $inventoryModel;
    protected PaymentModel $paymentModel;

    public function __construct()
    {
        $this->jobModel = new JobCardModel();
        $this->inventoryModel = new PartsInventoryModel();
        $this->paymentModel = new PaymentModel();
    }

    /**
     * Main dashboard view
     */
    public function index(): string
    {
        // Allow admin to filter by branch
        $branchId = $this->request->getGet('branch_id') 
            ? (int) $this->request->getGet('branch_id') 
            : $this->getBranchId();
        
        // For non-admin, always use their assigned branch
        if (!$this->isAdmin()) {
            $branchId = $this->getBranchId();
        }

        // Get job statistics
        $jobStats = $this->jobModel->getStats($branchId);

        // Get low stock items
        $lowStock = $this->inventoryModel->getLowStock($branchId);

        // Get today's and monthly revenue
        $today = date('Y-m-d');
        $todayRevenue = $this->paymentModel->getDailyRevenue($today, $branchId);
        $monthlyRevenue = $this->paymentModel->getMonthlyRevenue(
            (int) date('Y'),
            (int) date('m'),
            $branchId
        );

        // Get recent jobs with all relations (prevent N+1 queries)
        $builder = $this->jobModel
            ->select('job_cards.*, 
                customers.name as customer_name,
                branches.name as branch_name,
                users.name as technician_name')
            ->join('customers', 'customers.id = job_cards.customer_id')
            ->join('branches', 'branches.id = job_cards.branch_id', 'left')
            ->join('users', 'users.id = job_cards.technician_id', 'left')
            ->orderBy('job_cards.created_at', 'DESC')
            ->limit(10);
        
        // Apply branch filter for non-admin
        if ($branchId !== null) {
            $builder->where('job_cards.branch_id', $branchId);
        }
        
        $recentJobs = $builder->findAll();

        // Get jobs grouped by status for Kanban (technician view)
        $jobsByStatus = [];
        if (!$this->isAdmin()) {
            $jobsByStatus = $this->jobModel->getGroupedByStatus($branchId);
        }

        // Get revenue for last 7 days (for chart)
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $revenueChart[] = [
                'date'   => date('d M', strtotime($date)),
                'amount' => $this->paymentModel->getDailyRevenue($date, $branchId),
            ];
        }

        // Get all branches for admin filter
        $branchModel = new \App\Models\BranchModel();
        $branches = $branchModel->where('is_active', 1)->findAll();

        $data = $this->getViewData([
            'title'          => lang('App.dashboard'),
            'jobStats'       => $jobStats,
            'lowStock'       => $lowStock,
            'todayRevenue'   => $todayRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'recentJobs'     => $recentJobs,
            'jobsByStatus'   => $jobsByStatus,
            'revenueChart'   => $revenueChart,
            'branches'       => $branches,
            'selectedBranch' => $branchId,
        ]);

        // Show different view based on role
        if ($this->isAdmin()) {
            return view('dashboard/admin', $data);
        } else {
            return view('dashboard/technician', $data);
        }
    }

    /**
     * Get dashboard stats (AJAX)
     */
    public function getStats()
    {
        $branchId = $this->getBranchId();
        $stats = $this->jobModel->getStats($branchId);
        
        $today = date('Y-m-d');
        $stats['todayRevenue'] = $this->paymentModel->getDailyRevenue($today, $branchId);
        $stats['monthlyRevenue'] = $this->paymentModel->getMonthlyRevenue(
            (int) date('Y'),
            (int) date('m'),
            $branchId
        );
        $stats['lowStockCount'] = count($this->inventoryModel->getLowStock($branchId));

        return $this->successResponse('Stats retrieved', $stats);
    }
}
