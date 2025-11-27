<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\CustomerModel;
use App\Models\JobCardModel;

/**
 * Asset Controller
 * 
 * Manages ASIC miner assets.
 */
class AssetController extends BaseController
{
    protected AssetModel $assetModel;
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
        $this->customerModel = new CustomerModel();
    }

    /**
     * List all assets
     */
    public function index(): string
    {
        $status = $this->request->getGet('status');
        
        $builder = $this->assetModel
            ->select('assets.*, customers.name as customer_name, customers.phone as customer_phone')
            ->join('customers', 'customers.id = assets.customer_id');
        
        if ($status && in_array($status, ['stored', 'repairing', 'repaired', 'returned'])) {
            $builder->where('assets.status', $status);
        }

        $assets = $builder->orderBy('assets.created_at', 'DESC')->findAll();

        return view('assets/index', $this->getViewData([
            'title'         => lang('App.assets'),
            'assets'        => $assets,
            'currentStatus' => $status,
        ]));
    }

    /**
     * Create asset form (for manual add / storage)
     */
    public function create(): string
    {
        $customerId = $this->request->getGet('customer_id');
        $customer = null;
        
        if ($customerId) {
            $customer = $this->customerModel->find($customerId);
        }

        return view('assets/create', $this->getViewData([
            'title'    => lang('App.newAsset'),
            'customer' => $customer,
        ]));
    }

    /**
     * Store new asset
     */
    public function store()
    {
        $rules = [
            'customer_id'   => 'required|is_natural_no_zero',
            'brand_model'   => 'required|max_length[255]',
            'serial_number' => 'required|max_length[100]|is_unique[assets.serial_number]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'customer_id'        => $this->request->getPost('customer_id'),
            'brand_model'        => $this->request->getPost('brand_model'),
            'serial_number'      => $this->request->getPost('serial_number'),
            'mac_address'        => $this->request->getPost('mac_address'),
            'hash_rate'          => $this->request->getPost('hash_rate'),
            'external_condition' => $this->request->getPost('external_condition'),
            'status'             => 'stored', // Manual add = stored status
            'notes'              => $this->request->getPost('notes'),
        ];

        if ($this->assetModel->insert($data)) {
            return redirect()->to('/machines')
                ->with('success', lang('App.assetCreated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * View asset details with history
     */
    public function view(int $id): string
    {
        $asset = $this->assetModel->find($id);

        if (!$asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get customer
        $customer = $this->customerModel->find($asset['customer_id']);
        
        // Get job history
        $jobModel = new JobCardModel();
        $jobs = $jobModel->select('job_cards.*, assets.brand_model, assets.serial_number')
            ->join('assets', 'assets.id = job_cards.asset_id', 'left')
            ->where('job_cards.asset_id', $id)
            ->orderBy('job_cards.created_at', 'DESC')
            ->findAll();

        return view('assets/view', $this->getViewData([
            'title'    => $asset['serial_number'],
            'asset'    => $asset,
            'customer' => $customer,
            'jobs'     => $jobs,
        ]));
    }

    /**
     * Edit asset form
     */
    public function edit(int $id): string
    {
        $asset = $this->assetModel->find($id);

        if (!$asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customerModel->find($asset['customer_id']);

        return view('assets/edit', $this->getViewData([
            'title'    => lang('App.editAsset'),
            'asset'    => $asset,
            'customer' => $customer,
        ]));
    }

    /**
     * Update asset
     */
    public function update(int $id)
    {
        $asset = $this->assetModel->find($id);

        if (!$asset) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'brand_model'   => 'required|max_length[255]',
            'serial_number' => "required|max_length[100]|is_unique[assets.serial_number,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'brand_model'        => $this->request->getPost('brand_model'),
            'serial_number'      => $this->request->getPost('serial_number'),
            'mac_address'        => $this->request->getPost('mac_address'),
            'hash_rate'          => $this->request->getPost('hash_rate'),
            'external_condition' => $this->request->getPost('external_condition'),
            'notes'              => $this->request->getPost('notes'),
        ];

        if ($this->assetModel->update($id, $data)) {
            return redirect()->to('/machines/view/' . $id)
                ->with('success', lang('App.assetUpdated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Delete asset
     */
    public function delete(int $id)
    {
        $asset = $this->assetModel->find($id);

        if (!$asset) {
            return redirect()->to('/machines')
                ->with('error', lang('App.recordNotFound'));
        }

        // Check if asset has jobs
        $jobModel = new JobCardModel();
        $jobs = $jobModel->where('asset_id', $id)->countAllResults();
        
        if ($jobs > 0) {
            return redirect()->to('/machines')
                ->with('error', 'Cannot delete asset with repair history');
        }

        if ($this->assetModel->delete($id)) {
            return redirect()->to('/machines')
                ->with('success', lang('App.assetDeleted'));
        }

        return redirect()->to('/machines')
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Search assets (AJAX for autocomplete)
     */
    public function search()
    {
        $term = $this->request->getGet('term');
        
        if (strlen($term) < 2) {
            return $this->jsonResponse([]);
        }

        $assets = $this->assetModel->search($term, 10);

        return $this->jsonResponse($assets);
    }

    /**
     * Check if serial number exists (AJAX)
     */
    public function checkSerial()
    {
        $serial = $this->request->getGet('serial');
        
        if (empty($serial)) {
            return $this->jsonResponse(['exists' => false]);
        }

        $asset = $this->assetModel->findBySerialNumber($serial);

        if ($asset) {
            // Get customer info
            $customer = $this->customerModel->find($asset['customer_id']);
            $asset['customer'] = $customer;
        }

        return $this->jsonResponse([
            'exists' => $asset ? true : false,
            'asset'  => $asset,
        ]);
    }
}

