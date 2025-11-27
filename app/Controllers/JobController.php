<?php

namespace App\Controllers;

use App\Models\JobCardModel;
use App\Models\CustomerModel;
use App\Models\AssetModel;
use App\Models\BranchModel;
use App\Models\UserModel;
use App\Models\JobPartsModel;
use App\Models\PartsInventoryModel;
use App\Models\SymptomHistoryModel;
use App\Models\PaymentModel;

/**
 * Job Controller
 * 
 * Manages job cards / repair tickets.
 */
class JobController extends BaseController
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
     * List all jobs
     */
    public function index(): string
    {
        $status = $this->request->getGet('status');
        
        $builder = $this->jobModel
            ->select('job_cards.*, customers.name as customer_name, assets.serial_number, assets.brand_model')
            ->join('customers', 'customers.id = job_cards.customer_id')
            ->join('assets', 'assets.id = job_cards.asset_id');
        
        if ($status) {
            $builder->where('job_cards.status', $status);
        }

        $jobs = $builder->orderBy('job_cards.created_at', 'DESC')->findAll();

        return view('jobs/index', $this->getViewData([
            'title'         => lang('App.jobs'),
            'jobs'          => $jobs,
            'currentStatus' => $status,
        ]));
    }

    /**
     * Kanban board view
     */
    public function kanban(): string
    {
        $branchId = $this->getBranchId();
        $jobsByStatus = $this->jobModel->getGroupedByStatus($branchId);

        return view('jobs/kanban', $this->getViewData([
            'title'        => lang('App.kanbanBoard'),
            'jobsByStatus' => $jobsByStatus,
        ]));
    }

    /**
     * Create job form
     */
    public function create(): string
    {
        $userModel = new UserModel();
        $branchModel = new BranchModel();

        return view('jobs/create', $this->getViewData([
            'title'       => lang('App.newJobCard'),
            'technicians' => $userModel->getTechnicians(),
            'branches'    => $branchModel->getActive(),
        ]));
    }

    /**
     * Create job from existing asset
     */
    public function createFromAsset(int $assetId): string
    {
        $asset = $this->assetModel->getWithHistory($assetId);
        
        if (!$asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customerModel->find($asset['customer_id']);
        $userModel = new UserModel();
        $branchModel = new BranchModel();

        return view('jobs/create', $this->getViewData([
            'title'       => lang('App.newJobCard'),
            'asset'       => $asset,
            'customer'    => $customer,
            'technicians' => $userModel->getTechnicians(),
            'branches'    => $branchModel->getActive(),
        ]));
    }

    /**
     * Store new job
     */
    public function store()
    {
        $rules = [
            'customer_id'   => 'required|is_natural_no_zero',
            'brand_model'   => 'required|max_length[255]',
            'serial_number' => 'required|max_length[100]',
            'symptom'       => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $customerId = $this->request->getPost('customer_id');

        // Handle customer creation if needed
        if ($this->request->getPost('new_customer') === '1') {
            $customerData = [
                'name'  => $this->request->getPost('customer_name'),
                'phone' => $this->request->getPost('customer_phone'),
                'email' => $this->request->getPost('customer_email'),
            ];
            $this->customerModel->insert($customerData);
            $customerId = $this->customerModel->getInsertID();
        }

        // Get or create asset
        $assetData = [
            'customer_id'        => $customerId,
            'brand_model'        => $this->request->getPost('brand_model'),
            'serial_number'      => $this->request->getPost('serial_number'),
            'mac_address'        => $this->request->getPost('mac_address'),
            'hash_rate'          => $this->request->getPost('hash_rate'),
            'external_condition' => $this->request->getPost('external_condition'),
        ];
        $asset = $this->assetModel->getOrCreate($assetData);

        // Create job
        $jobData = [
            'customer_id'       => $customerId,
            'asset_id'          => $asset['id'],
            'branch_id'         => $this->request->getPost('branch_id') ?: $this->getBranchId(),
            'technician_id'     => $this->request->getPost('technician_id'),
            'symptom'           => $this->request->getPost('symptom'),
            'notes'             => $this->request->getPost('notes'),
            'status'            => JobCardModel::STATUS_NEW_CHECKIN,
            'is_warranty_claim' => $this->request->getPost('is_warranty_claim') ? 1 : 0,
            'original_job_id'   => $this->request->getPost('original_job_id'),
            'labor_cost'        => $this->request->getPost('labor_cost') ?: 0,
            'created_by'        => $this->getUserId(),
        ];

        if ($this->jobModel->insert($jobData)) {
            $jobId = $this->jobModel->getInsertID();
            $job = $this->jobModel->find($jobId);
            
            return redirect()->to('/jobs/view/' . $jobId)
                ->with('success', lang('App.jobCreated') . ' - ' . $job['job_id']);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * View job details
     */
    public function view(int $id): string
    {
        $job = $this->jobModel->getWithDetails($id);

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $inventoryModel = new PartsInventoryModel();

        return view('jobs/view', $this->getViewData([
            'title' => lang('App.jobCard') . ' #' . $job['job_id'],
            'job'   => $job,
            'parts' => $inventoryModel->where('is_active', 1)->findAll(),
        ]));
    }

    /**
     * Edit job form
     */
    public function edit(int $id): string
    {
        $job = $this->jobModel->getWithDetails($id);

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if job is locked
        if ($job['is_locked']) {
            return redirect()->to('/jobs/view/' . $id)
                ->with('error', 'This job is locked and cannot be edited.');
        }

        $userModel = new UserModel();

        return view('jobs/edit', $this->getViewData([
            'title'       => lang('App.editJobCard') . ' #' . $job['job_id'],
            'job'         => $job,
            'technicians' => $userModel->getTechnicians(),
        ]));
    }

    /**
     * Update job
     */
    public function update(int $id)
    {
        $job = $this->jobModel->find($id);

        if (!$job || $job['is_locked']) {
            return redirect()->to('/jobs')
                ->with('error', lang('App.operationFailed'));
        }

        $data = [
            'technician_id' => $this->request->getPost('technician_id'),
            'symptom'       => $this->request->getPost('symptom'),
            'diagnosis'     => $this->request->getPost('diagnosis'),
            'solution'      => $this->request->getPost('solution'),
            'notes'         => $this->request->getPost('notes'),
            'labor_cost'    => $this->request->getPost('labor_cost') ?: 0,
        ];

        if ($this->jobModel->update($id, $data)) {
            // Recalculate totals
            $this->jobModel->calculateTotals($id);
            
            return redirect()->to('/jobs/view/' . $id)
                ->with('success', lang('App.jobUpdated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Update job status (AJAX for Kanban)
     */
    public function updateStatus(int $id)
    {
        $job = $this->jobModel->find($id);

        if (!$job) {
            return $this->errorResponse(lang('App.recordNotFound'), 404);
        }

        if ($job['is_locked']) {
            return $this->errorResponse('Job is locked', 403);
        }

        $newStatus = $this->request->getPost('status');
        $validStatuses = [
            JobCardModel::STATUS_NEW_CHECKIN,
            JobCardModel::STATUS_PENDING_REPAIR,
            JobCardModel::STATUS_IN_PROGRESS,
            JobCardModel::STATUS_REPAIR_DONE,
            JobCardModel::STATUS_READY_HANDOVER,
            JobCardModel::STATUS_DELIVERED,
        ];

        if (!in_array($newStatus, $validStatuses)) {
            return $this->errorResponse('Invalid status', 400);
        }

        // Check payment before marking as delivered
        if ($newStatus === JobCardModel::STATUS_DELIVERED) {
            if ($job['amount_paid'] < $job['grand_total']) {
                return $this->errorResponse(lang('App.insufficientPayment'), 400);
            }
        }

        if ($this->jobModel->update($id, ['status' => $newStatus])) {
            return $this->successResponse('Status updated');
        }

        return $this->errorResponse(lang('App.operationFailed'));
    }

    /**
     * Cancel job
     */
    public function cancel(int $id)
    {
        $job = $this->jobModel->find($id);

        if (!$job || $job['is_locked']) {
            return redirect()->to('/jobs')
                ->with('error', lang('App.operationFailed'));
        }

        $reason = $this->request->getPost('reason');

        if ($this->jobModel->update($id, [
            'status'        => JobCardModel::STATUS_CANCELLED,
            'cancel_reason' => $reason,
        ])) {
            return redirect()->to('/jobs')
                ->with('success', lang('App.jobCancelled'));
        }

        return redirect()->back()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Print check-in slip
     */
    public function print(int $id): string
    {
        $job = $this->jobModel->getWithDetails($id);

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('jobs/print', [
            'job' => $job,
        ]);
    }

    /**
     * Print A4 label
     */
    public function printLabel(int $id): string
    {
        $job = $this->jobModel->getWithDetails($id);

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('jobs/print_label', [
            'job' => $job,
        ]);
    }

    /**
     * Export Job Card as PDF
     */
    public function exportPdf(int $id)
    {
        $job = $this->jobModel->find($id);

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customerModel->find($job['customer_id']);
        $asset = $this->assetModel->find($job['asset_id']);
        
        // Get parts used
        $jobPartsModel = new \App\Models\JobPartsModel();
        $parts = $jobPartsModel->select('job_parts.*, parts_inventory.part_code, parts_inventory.name')
            ->join('parts_inventory', 'parts_inventory.id = job_parts.part_id')
            ->where('job_parts.job_id', $id)
            ->findAll();

        $pdfGenerator = new \App\Libraries\PdfGenerator();
        $pdf = $pdfGenerator->generateJobCard($job, $customer, $asset, $parts);
        
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="JobCard-' . $job['job_id'] . '.pdf"')
            ->setBody($pdf);
    }

    /**
     * Export Receipt as PDF
     */
    public function exportReceipt(int $id)
    {
        $job = $this->jobModel->find($id);

        if (!$job) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customerModel->find($job['customer_id']);
        
        // Get payments
        $paymentModel = new \App\Models\PaymentModel();
        $payments = $paymentModel->where('job_id', $id)->findAll();

        $pdfGenerator = new \App\Libraries\PdfGenerator();
        $pdf = $pdfGenerator->generateReceipt($job, $customer, $payments);
        
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Receipt-' . $job['job_id'] . '.pdf"')
            ->setBody($pdf);
    }

    /**
     * Add part to job
     */
    public function addPart(int $id)
    {
        $job = $this->jobModel->find($id);

        if (!$job || $job['is_locked']) {
            return $this->errorResponse(lang('App.operationFailed'));
        }

        $partId = $this->request->getPost('part_id');
        $quantity = (int) $this->request->getPost('quantity') ?: 1;

        $jobPartsModel = new JobPartsModel();
        
        if ($jobPartsModel->addPartToJob($id, $partId, $quantity, null, $this->getUserId())) {
            return $this->successResponse(lang('App.operationSuccess'));
        }

        return $this->errorResponse(lang('App.operationFailed'));
    }

    /**
     * Remove part from job
     */
    public function removePart(int $id)
    {
        $jobPartId = $this->request->getPost('job_part_id');
        
        $jobPartsModel = new JobPartsModel();
        $jobPart = $jobPartsModel->find($jobPartId);

        if (!$jobPart || $jobPart['job_card_id'] != $id) {
            return $this->errorResponse(lang('App.recordNotFound'));
        }

        // Check if already deducted
        if ($jobPart['is_deducted']) {
            return $this->errorResponse('Cannot remove - stock already deducted');
        }

        if ($jobPartsModel->removePartFromJob($jobPartId)) {
            return $this->successResponse(lang('App.operationSuccess'));
        }

        return $this->errorResponse(lang('App.operationFailed'));
    }

    /**
     * Get symptoms for autocomplete
     */
    public function getSymptoms()
    {
        $term = $this->request->getGet('term');
        
        $symptomModel = new SymptomHistoryModel();
        
        if ($term) {
            $symptoms = $symptomModel->search($term, 10);
        } else {
            $symptoms = $symptomModel->getTopSymptoms(20);
        }

        return $this->jsonResponse($symptoms);
    }

    /**
     * Get jobs by status (AJAX for Kanban)
     */
    public function getByStatus()
    {
        $branchId = $this->getBranchId();
        $jobsByStatus = $this->jobModel->getGroupedByStatus($branchId);

        return $this->successResponse('Jobs retrieved', $jobsByStatus);
    }

    /**
     * Update job order (AJAX for Kanban drag-drop)
     */
    public function updateOrder()
    {
        // For future implementation of job ordering within columns
        return $this->successResponse('Order updated');
    }
}

