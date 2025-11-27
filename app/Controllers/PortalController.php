<?php

namespace App\Controllers;

use App\Models\JobCardModel;
use App\Models\CustomerModel;
use App\Models\AssetModel;

/**
 * Portal Controller
 * 
 * Public-facing portal for customers to track their job status.
 */
class PortalController extends BaseController
{
    protected JobCardModel $jobModel;
    protected CustomerModel $customerModel;
    protected AssetModel $assetModel;

    public function __construct()
    {
        $this->jobModel = new JobCardModel();
        $this->customerModel = new CustomerModel();
        $this->assetModel = new AssetModel();
    }

    /**
     * Track job status page
     */
    public function track(): string
    {
        $jobId = $this->request->getGet('job_id');
        $job = null;
        $customer = null;
        $asset = null;
        $timeline = [];
        $error = null;

        if ($jobId) {
            // Search by job_id (formatted ID like 2411001)
            $job = $this->jobModel->where('job_id', $jobId)->first();
            
            if (!$job) {
                // Try searching by ID number
                $job = $this->jobModel->find($jobId);
            }

            if ($job) {
                $customer = $this->customerModel->find($job['customer_id']);
                $asset = $this->assetModel->find($job['asset_id']);
                $timeline = $this->getJobTimeline($job);
            } else {
                $error = 'Job not found. Please check your Job ID and try again.';
            }
        }

        return view('portal/track', [
            'title'    => 'Track Your Repair',
            'jobId'    => $jobId,
            'job'      => $job,
            'customer' => $customer,
            'asset'    => $asset,
            'timeline' => $timeline,
            'error'    => $error,
        ]);
    }

    /**
     * Get job timeline for tracking
     */
    protected function getJobTimeline(array $job): array
    {
        $timeline = [];
        
        // Define status order and descriptions
        $statuses = [
            'new_checkin' => [
                'label' => 'Checked In',
                'description' => 'Your device has been received and logged into our system.',
                'icon' => 'bi-inbox',
                'color' => 'info'
            ],
            'pending_repair' => [
                'label' => 'Awaiting Repair',
                'description' => 'Your device is in queue waiting for a technician.',
                'icon' => 'bi-hourglass-split',
                'color' => 'warning'
            ],
            'in_progress' => [
                'label' => 'Repair In Progress',
                'description' => 'A technician is currently working on your device.',
                'icon' => 'bi-tools',
                'color' => 'primary'
            ],
            'repair_done' => [
                'label' => 'Repair Complete',
                'description' => 'The repair has been completed. Awaiting quality check.',
                'icon' => 'bi-check-circle',
                'color' => 'success'
            ],
            'ready_handover' => [
                'label' => 'Ready for Pickup',
                'description' => 'Your device is ready! Please come to pick it up.',
                'icon' => 'bi-box-seam',
                'color' => 'success'
            ],
            'delivered' => [
                'label' => 'Delivered',
                'description' => 'Your device has been returned to you.',
                'icon' => 'bi-check2-all',
                'color' => 'secondary'
            ],
        ];

        $currentStatusIndex = array_search($job['status'], array_keys($statuses));
        $index = 0;

        foreach ($statuses as $status => $info) {
            $isCompleted = $index < $currentStatusIndex;
            $isCurrent = $index === $currentStatusIndex;
            
            $timeline[] = [
                'status'      => $status,
                'label'       => $info['label'],
                'description' => $info['description'],
                'icon'        => $info['icon'],
                'color'       => $info['color'],
                'completed'   => $isCompleted,
                'current'     => $isCurrent,
                'pending'     => !$isCompleted && !$isCurrent,
            ];
            
            $index++;
        }

        return $timeline;
    }

    /**
     * Customer history page (requires phone verification)
     */
    public function history(): string
    {
        $phone = $this->request->getGet('phone');
        $jobs = [];
        $customer = null;

        if ($phone) {
            $customer = $this->customerModel->where('phone', $phone)->first();
            
            if ($customer) {
                $jobs = $this->jobModel
                    ->select('job_cards.*, assets.brand_model, assets.serial_number')
                    ->join('assets', 'assets.id = job_cards.asset_id')
                    ->where('job_cards.customer_id', $customer['id'])
                    ->orderBy('job_cards.created_at', 'DESC')
                    ->findAll();
            }
        }

        return view('portal/history', [
            'title'    => 'Repair History',
            'phone'    => $phone,
            'customer' => $customer,
            'jobs'     => $jobs,
        ]);
    }
}

