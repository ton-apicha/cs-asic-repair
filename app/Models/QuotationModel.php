<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Traits\AuditTrait;

class QuotationModel extends Model
{
    use AuditTrait;
    
    protected $table = 'quotations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'quotation_no',
        'customer_id',
        'branch_id',
        'asset_id',
        'description',
        'subtotal',
        'include_vat',
        'vat_amount',
        'grand_total',
        'status',
        'valid_until',
        'notes',
        'converted_job_id',
        'created_by',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Status constants
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SENT = 'sent';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CONVERTED = 'converted';

    /**
     * Generate next quotation number
     */
    public function generateQuotationNo(): string
    {
        $prefix = 'QT';
        $year = date('y');
        $month = date('m');
        
        // Get last quotation number for this month
        $lastQuotation = $this->where("quotation_no LIKE '{$prefix}{$year}{$month}%'")
            ->orderBy('quotation_no', 'DESC')
            ->first();
        
        if ($lastQuotation) {
            $lastNumber = (int) substr($lastQuotation['quotation_no'], -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get quotation with customer and items
     */
    public function getWithDetails(int $id): ?array
    {
        $quotation = $this->select('quotations.*, customers.name as customer_name, customers.phone as customer_phone')
            ->join('customers', 'customers.id = quotations.customer_id')
            ->find($id);
        
        if ($quotation) {
            $itemModel = new QuotationItemModel();
            $quotation['items'] = $itemModel->where('quotation_id', $id)->findAll();
        }
        
        return $quotation;
    }

    /**
     * Calculate and update totals
     */
    public function recalculateTotals(int $quotationId): void
    {
        $itemModel = new QuotationItemModel();
        $items = $itemModel->where('quotation_id', $quotationId)->findAll();
        
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['total'];
        }
        
        $quotation = $this->find($quotationId);
        $vatAmount = $quotation['include_vat'] ? $subtotal * 0.07 : 0;
        $grandTotal = $subtotal + $vatAmount;
        
        $this->update($quotationId, [
            'subtotal'    => $subtotal,
            'vat_amount'  => $vatAmount,
            'grand_total' => $grandTotal,
        ]);
    }
}

