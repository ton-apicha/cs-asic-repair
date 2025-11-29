<?php

namespace App\Controllers;

use App\Models\QuotationModel;
use App\Models\QuotationItemModel;
use App\Models\CustomerModel;
use App\Models\AssetModel;
use App\Models\PartsInventoryModel;
use App\Models\JobCardModel;

/**
 * Quotation Controller
 * 
 * Manages quotation creation, editing, and conversion to job cards.
 */
class QuotationController extends BaseController
{
    protected QuotationModel $quotationModel;
    protected QuotationItemModel $itemModel;
    protected CustomerModel $customerModel;
    protected AssetModel $assetModel;

    public function __construct()
    {
        $this->quotationModel = new QuotationModel();
        $this->itemModel = new QuotationItemModel();
        $this->customerModel = new CustomerModel();
        $this->assetModel = new AssetModel();
    }

    /**
     * List all quotations
     */
    public function index(): string
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        
        $builder = $this->quotationModel
            ->select('quotations.*, customers.name as customer_name')
            ->join('customers', 'customers.id = quotations.customer_id');
        
        if ($status) {
            $builder->where('quotations.status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('quotations.quotation_no', $search)
                ->orLike('customers.name', $search)
                ->groupEnd();
        }
        
        $quotations = $builder->orderBy('quotations.created_at', 'DESC')->paginate(20);
        
        return view('quotations/index', $this->getViewData([
            'title'       => 'Quotations',
            'quotations'  => $quotations,
            'pager'       => $this->quotationModel->pager,
            'status'      => $status,
            'search'      => $search,
        ]));
    }

    /**
     * Create new quotation form
     */
    public function create(): string
    {
        $customers = $this->customerModel->orderBy('name')->findAll();
        $parts = (new PartsInventoryModel())->where('quantity >', 0)->findAll();
        
        return view('quotations/create', $this->getViewData([
            'title'     => 'Create Quotation',
            'customers' => $customers,
            'parts'     => $parts,
        ]));
    }

    /**
     * Store new quotation
     */
    public function store()
    {
        $rules = [
            'customer_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $quotationNo = $this->quotationModel->generateQuotationNo();
        $includeVat = (bool) $this->request->getPost('include_vat');
        
        $quotationId = $this->quotationModel->insert([
            'quotation_no' => $quotationNo,
            'customer_id'  => $this->request->getPost('customer_id'),
            'asset_id'     => $this->request->getPost('asset_id') ?: null,
            'branch_id'    => $this->getBranchId(),
            'description'  => $this->request->getPost('description'),
            'include_vat'  => $includeVat,
            'valid_until'  => date('Y-m-d', strtotime('+30 days')),
            'notes'        => $this->request->getPost('notes'),
            'created_by'   => $this->getUserId(),
            'status'       => QuotationModel::STATUS_DRAFT,
        ]);

        if ($quotationId === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create quotation. Please try again.');
        }

        // Add items
        $items = $this->request->getPost('items') ?? [];
        foreach ($items as $item) {
            if (!empty($item['name']) && $item['quantity'] > 0) {
                $this->itemModel->insert([
                    'quotation_id' => $quotationId,
                    'item_type'    => $item['type'] ?? 'service',
                    'part_id'      => $item['part_id'] ?? null,
                    'name'         => $item['name'],
                    'description'  => $item['description'] ?? null,
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'total'        => $item['quantity'] * $item['unit_price'],
                ]);
            }
        }

        // Recalculate totals
        $this->quotationModel->recalculateTotals($quotationId);

        return redirect()->to('/quotations/view/' . $quotationId)
            ->with('success', 'Quotation created successfully');
    }

    /**
     * View quotation details
     */
    public function view(int $id): string
    {
        $quotation = $this->quotationModel->getWithDetails($id);

        if (!$quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customerModel->find($quotation['customer_id']);
        $asset = $quotation['asset_id'] ? $this->assetModel->find($quotation['asset_id']) : null;

        return view('quotations/view', $this->getViewData([
            'title'     => 'Quotation ' . $quotation['quotation_no'],
            'quotation' => $quotation,
            'customer'  => $customer,
            'asset'     => $asset,
        ]));
    }

    /**
     * Edit quotation form
     */
    public function edit(int $id): string
    {
        $quotation = $this->quotationModel->getWithDetails($id);

        if (!$quotation || $quotation['status'] !== QuotationModel::STATUS_DRAFT) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customers = $this->customerModel->orderBy('name')->findAll();
        $parts = (new PartsInventoryModel())->where('quantity >', 0)->findAll();

        return view('quotations/edit', $this->getViewData([
            'title'     => 'Edit Quotation',
            'quotation' => $quotation,
            'customers' => $customers,
            'parts'     => $parts,
        ]));
    }

    /**
     * Update quotation
     */
    public function update(int $id)
    {
        $quotation = $this->quotationModel->find($id);

        if (!$quotation || $quotation['status'] !== QuotationModel::STATUS_DRAFT) {
            return redirect()->back()->with('error', 'Cannot edit this quotation');
        }

        $includeVat = (bool) $this->request->getPost('include_vat');
        
        $this->quotationModel->update($id, [
            'customer_id'  => $this->request->getPost('customer_id'),
            'asset_id'     => $this->request->getPost('asset_id') ?: null,
            'description'  => $this->request->getPost('description'),
            'include_vat'  => $includeVat,
            'notes'        => $this->request->getPost('notes'),
        ]);

        // Delete existing items and add new ones
        $this->itemModel->where('quotation_id', $id)->delete();
        
        $items = $this->request->getPost('items') ?? [];
        foreach ($items as $item) {
            if (!empty($item['name']) && $item['quantity'] > 0) {
                $this->itemModel->insert([
                    'quotation_id' => $id,
                    'item_type'    => $item['type'] ?? 'service',
                    'part_id'      => $item['part_id'] ?? null,
                    'name'         => $item['name'],
                    'description'  => $item['description'] ?? null,
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'total'        => $item['quantity'] * $item['unit_price'],
                ]);
            }
        }

        $this->quotationModel->recalculateTotals($id);

        return redirect()->to('/quotations/view/' . $id)
            ->with('success', 'Quotation updated successfully');
    }

    /**
     * Update quotation status
     */
    public function updateStatus(int $id)
    {
        $quotation = $this->quotationModel->find($id);
        $newStatus = $this->request->getPost('status');

        if (!$quotation) {
            return $this->errorResponse('Quotation not found');
        }

        $validTransitions = [
            'draft' => ['sent', 'approved'],
            'sent' => ['approved', 'rejected', 'expired'],
            'approved' => ['converted'],
        ];

        if (!isset($validTransitions[$quotation['status']]) || 
            !in_array($newStatus, $validTransitions[$quotation['status']])) {
            return $this->errorResponse('Invalid status transition');
        }

        $this->quotationModel->update($id, ['status' => $newStatus]);

        return $this->successResponse('Status updated', ['status' => $newStatus]);
    }

    /**
     * Convert quotation to job card
     */
    public function convertToJob(int $id)
    {
        $quotation = $this->quotationModel->getWithDetails($id);

        if (!$quotation || $quotation['status'] !== QuotationModel::STATUS_APPROVED) {
            return redirect()->back()->with('error', 'Quotation must be approved to convert');
        }

        $jobModel = new JobCardModel();
        
        // Generate job ID
        $jobId = $jobModel->generateJobId();
        
        // Create job card from quotation
        $newJobId = $jobModel->insert([
            'job_id'       => $jobId,
            'customer_id'  => $quotation['customer_id'],
            'asset_id'     => $quotation['asset_id'],
            'branch_id'    => $quotation['branch_id'],
            'symptom'      => $quotation['description'],
            'labor_cost'   => 0,
            'parts_cost'   => 0,
            'subtotal'     => $quotation['subtotal'],
            'vat_amount'   => $quotation['vat_amount'],
            'grand_total'  => $quotation['grand_total'],
            'include_vat'  => $quotation['include_vat'],
            'notes'        => 'Converted from quotation: ' . $quotation['quotation_no'],
            'checkin_date' => date('Y-m-d H:i:s'),
            'status'       => JobCardModel::STATUS_NEW_CHECKIN,
            'created_by'   => $this->getUserId(),
        ]);

        if ($newJobId === false) {
            return redirect()->back()
                ->with('error', 'Failed to create job card. Please try again.');
        }

        // Update quotation status
        $this->quotationModel->update($id, [
            'status'           => QuotationModel::STATUS_CONVERTED,
            'converted_job_id' => $newJobId,
        ]);

        return redirect()->to('/jobs/view/' . $newJobId)
            ->with('success', 'Quotation converted to Job Card: ' . $jobId);
    }

    /**
     * Export quotation as PDF
     */
    public function exportPdf(int $id)
    {
        $quotation = $this->quotationModel->getWithDetails($id);

        if (!$quotation) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customerModel->find($quotation['customer_id']);
        
        $pdfGenerator = new \App\Libraries\PdfGenerator();
        $pdf = $pdfGenerator->generateQuotation($quotation, $customer, $quotation['items']);
        
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Quotation-' . $quotation['quotation_no'] . '.pdf"')
            ->setBody($pdf);
    }

    /**
     * Delete quotation (draft only)
     */
    public function delete(int $id)
    {
        $quotation = $this->quotationModel->find($id);

        if (!$quotation || $quotation['status'] !== QuotationModel::STATUS_DRAFT) {
            return redirect()->back()->with('error', 'Cannot delete this quotation');
        }

        $this->itemModel->where('quotation_id', $id)->delete();
        $this->quotationModel->delete($id);

        return redirect()->to('/quotations')->with('success', 'Quotation deleted');
    }
}

