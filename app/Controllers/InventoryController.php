<?php

namespace App\Controllers;

use App\Models\PartsInventoryModel;
use App\Models\StockTransactionModel;

/**
 * Inventory Controller
 * 
 * Manages parts inventory.
 */
class InventoryController extends BaseController
{
    protected PartsInventoryModel $inventoryModel;

    public function __construct()
    {
        $this->inventoryModel = new PartsInventoryModel();
    }

    /**
     * List all parts
     */
    public function index(): string
    {
        $category = $this->request->getGet('category');
        
        $builder = $this->inventoryModel->where('is_active', 1);
        
        if ($category) {
            $builder->where('category', $category);
        }

        $parts = $builder->orderBy('name', 'ASC')->findAll();
        $categories = $this->inventoryModel->getCategories();

        return view('inventory/index', $this->getViewData([
            'title'           => lang('App.inventory'),
            'parts'           => $parts,
            'categories'      => $categories,
            'currentCategory' => $category,
        ]));
    }

    /**
     * Create part form
     */
    public function create(): string
    {
        // Admin only
        if (!$this->isAdmin()) {
            return redirect()->to('/inventory')
                ->with('error', lang('App.accessDenied'));
        }

        $categories = $this->inventoryModel->getCategories();

        return view('inventory/create', $this->getViewData([
            'title'      => lang('App.newPart'),
            'categories' => $categories,
        ]));
    }

    /**
     * Store new part
     */
    public function store()
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/inventory')
                ->with('error', lang('App.accessDenied'));
        }

        $rules = [
            'part_code'  => 'required|max_length[50]|is_unique[parts_inventory.part_code]',
            'name'       => 'required|max_length[255]',
            'cost_price' => 'required|numeric|greater_than_equal_to[0]',
            'sell_price' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'part_code'     => $this->request->getPost('part_code'),
            'name'          => $this->request->getPost('name'),
            'description'   => $this->request->getPost('description'),
            'serial_number' => $this->request->getPost('serial_number'),
            'cost_price'    => $this->request->getPost('cost_price'),
            'sell_price'    => $this->request->getPost('sell_price'),
            'quantity'      => $this->request->getPost('quantity') ?: 0,
            'reorder_point' => $this->request->getPost('reorder_point') ?: 5,
            'location'      => $this->request->getPost('location'),
            'category'      => $this->request->getPost('category'),
            'notes'         => $this->request->getPost('notes'),
        ];

        if ($this->inventoryModel->insert($data)) {
            return redirect()->to('/inventory')
                ->with('success', lang('App.partCreated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * View part details
     */
    public function view(int $id): string
    {
        $part = $this->inventoryModel->find($id);

        if (!$part) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $transactionModel = new StockTransactionModel();
        $transactions = $transactionModel->getByPart($id, 50);

        return view('inventory/view', $this->getViewData([
            'title'        => $part['name'],
            'part'         => $part,
            'transactions' => $transactions,
        ]));
    }

    /**
     * Edit part form
     */
    public function edit(int $id): string
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/inventory')
                ->with('error', lang('App.accessDenied'));
        }

        $part = $this->inventoryModel->find($id);

        if (!$part) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $categories = $this->inventoryModel->getCategories();

        return view('inventory/edit', $this->getViewData([
            'title'      => lang('App.editPart'),
            'part'       => $part,
            'categories' => $categories,
        ]));
    }

    /**
     * Update part
     */
    public function update(int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/inventory')
                ->with('error', lang('App.accessDenied'));
        }

        $part = $this->inventoryModel->find($id);

        if (!$part) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'part_code'  => "required|max_length[50]|is_unique[parts_inventory.part_code,id,{$id}]",
            'name'       => 'required|max_length[255]',
            'cost_price' => 'required|numeric|greater_than_equal_to[0]',
            'sell_price' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Check if quantity changed - need to log adjustment
        $newQuantity = (int) $this->request->getPost('quantity');
        if ($newQuantity != $part['quantity']) {
            $this->inventoryModel->adjustStock(
                $id,
                $newQuantity,
                'Manual adjustment',
                $this->getUserId()
            );
        }

        $data = [
            'part_code'     => $this->request->getPost('part_code'),
            'name'          => $this->request->getPost('name'),
            'description'   => $this->request->getPost('description'),
            'serial_number' => $this->request->getPost('serial_number'),
            'cost_price'    => $this->request->getPost('cost_price'),
            'sell_price'    => $this->request->getPost('sell_price'),
            'reorder_point' => $this->request->getPost('reorder_point') ?: 5,
            'location'      => $this->request->getPost('location'),
            'category'      => $this->request->getPost('category'),
            'notes'         => $this->request->getPost('notes'),
        ];

        if ($this->inventoryModel->update($id, $data)) {
            return redirect()->to('/inventory')
                ->with('success', lang('App.partUpdated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Delete part
     */
    public function delete(int $id)
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/inventory')
                ->with('error', lang('App.accessDenied'));
        }

        $part = $this->inventoryModel->find($id);

        if (!$part) {
            return redirect()->to('/inventory')
                ->with('error', lang('App.recordNotFound'));
        }

        if ($this->inventoryModel->delete($id)) {
            return redirect()->to('/inventory')
                ->with('success', lang('App.partDeleted'));
        }

        return redirect()->to('/inventory')
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Search parts (AJAX)
     */
    public function search()
    {
        $term = $this->request->getGet('term');
        
        if (strlen($term) < 2) {
            return $this->jsonResponse([]);
        }

        $parts = $this->inventoryModel->search($term, 10);

        return $this->jsonResponse($parts);
    }

    /**
     * Low stock alert
     */
    public function lowStock(): string
    {
        $parts = $this->inventoryModel->getLowStock($this->getBranchId());

        return view('inventory/low_stock', $this->getViewData([
            'title' => lang('App.lowStockAlert'),
            'parts' => $parts,
        ]));
    }

    /**
     * Stock transactions for a part
     */
    public function transactions(int $id): string
    {
        $part = $this->inventoryModel->find($id);

        if (!$part) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $transactionModel = new StockTransactionModel();
        $transactions = $transactionModel->getByPart($id, 100);

        return view('inventory/transactions', $this->getViewData([
            'title'        => lang('App.stockTransaction') . ' - ' . $part['name'],
            'part'         => $part,
            'transactions' => $transactions,
        ]));
    }
}

