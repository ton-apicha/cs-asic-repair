<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\AssetModel;
use App\Models\JobCardModel;

/**
 * Customer Controller
 * 
 * Manages customer CRUD operations.
 */
class CustomerController extends BaseController
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    /**
     * List all customers
     */
    public function index(): string
    {
        $branchFilter = $this->getBranchFilter();
        $customers = $this->customerModel->getAllWithBranchFilter($branchFilter);

        return view('customers/index', $this->getViewData([
            'title'     => lang('App.customers'),
            'customers' => $customers,
        ]));
    }

    /**
     * Create customer form
     */
    public function create(): string
    {
        return view('customers/create', $this->getViewData([
            'title' => lang('App.newCustomer'),
        ]));
    }

    /**
     * Store new customer
     */
    public function store()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[255]',
            'phone'   => 'required|regex_match[/^[0-9\-\+\(\)\s]{9,20}$/]|max_length[50]',
            'email'   => 'permit_empty|valid_email|max_length[255]',
            'tax_id'  => 'permit_empty|max_length[50]',
            'address' => 'permit_empty|max_length[65535]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Get branch_id for the new customer
        $requestedBranchId = $this->request->getPost('branch_id') ? (int)$this->request->getPost('branch_id') : null;
        
        $data = [
            'branch_id' => $this->getCreateBranchId($requestedBranchId),
            'name'      => $this->request->getPost('name'),
            'phone'     => $this->request->getPost('phone'),
            'email'     => $this->request->getPost('email'),
            'address'   => $this->request->getPost('address'),
            'tax_id'    => $this->request->getPost('tax_id'),
            'notes'     => $this->request->getPost('notes'),
        ];

        if ($this->customerModel->insert($data)) {
            return redirect()->to('/customers')
                ->with('success', lang('App.customerCreated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * View customer details
     */
    public function view(int $id): string
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // Check branch access
        if (!$this->canAccessBranch($customer['branch_id'] ?? null)) {
            return redirect()->to('/customers')
                ->with('error', lang('App.accessDenied'));
        }

        // Get assets
        $assetModel = new \App\Models\AssetModel();
        $assets = $assetModel->where('customer_id', $id)->findAll();

        // Get jobs
        $jobModel = new \App\Models\JobCardModel();
        $jobs = $jobModel->select('job_cards.*, assets.brand_model, assets.serial_number')
            ->join('assets', 'assets.id = job_cards.asset_id', 'left')
            ->where('job_cards.customer_id', $id)
            ->orderBy('job_cards.created_at', 'DESC')
            ->findAll();

        return view('customers/view', $this->getViewData([
            'title'    => $customer['name'],
            'customer' => $customer,
            'assets'   => $assets,
            'jobs'     => $jobs,
        ]));
    }

    /**
     * Edit customer form
     */
    public function edit(int $id): string
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        // Check branch access
        if (!$this->canAccessBranch($customer['branch_id'] ?? null)) {
            return redirect()->to('/customers')
                ->with('error', lang('App.accessDenied'));
        }

        return view('customers/edit', $this->getViewData([
            'title'    => lang('App.editCustomer'),
            'customer' => $customer,
        ]));
    }

    /**
     * Update customer
     */
    public function update(int $id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name'    => 'required|min_length[2]|max_length[255]',
            'phone'   => 'required|regex_match[/^[0-9\-\+\(\)\s]{9,20}$/]|max_length[50]',
            'email'   => 'permit_empty|valid_email|max_length[255]',
            'tax_id'  => 'permit_empty|max_length[50]',
            'address' => 'permit_empty|max_length[65535]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'    => $this->request->getPost('name'),
            'phone'   => $this->request->getPost('phone'),
            'email'   => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
            'tax_id'  => $this->request->getPost('tax_id'),
            'notes'   => $this->request->getPost('notes'),
        ];

        if ($this->customerModel->update($id, $data)) {
            return redirect()->to('/customers')
                ->with('success', lang('App.customerUpdated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Delete customer
     */
    public function delete(int $id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to('/customers')
                ->with('error', lang('App.recordNotFound'));
        }

        if ($this->customerModel->delete($id)) {
            return redirect()->to('/customers')
                ->with('success', lang('App.customerDeleted'));
        }

        return redirect()->to('/customers')
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Search customers (AJAX for autocomplete)
     */
    public function search()
    {
        $term = $this->request->getGet('term');
        
        if (strlen($term) < 2) {
            return $this->jsonResponse([]);
        }

        $branchFilter = $this->getBranchFilter();
        $customers = $this->customerModel->search($term, 10, $branchFilter);

        return $this->jsonResponse($customers);
    }

    /**
     * Get customer history (AJAX)
     */
    public function history(int $id)
    {
        $customer = $this->customerModel->getWithHistory($id);

        if (!$customer) {
            return $this->errorResponse(lang('App.recordNotFound'), 404);
        }

        return $this->successResponse('History retrieved', $customer);
    }
}

