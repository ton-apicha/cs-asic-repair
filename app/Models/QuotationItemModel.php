<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotationItemModel extends Model
{
    protected $table = 'quotation_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'quotation_id',
        'item_type',
        'part_id',
        'name',
        'description',
        'quantity',
        'unit_price',
        'total',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    /**
     * Calculate total before saving
     */
    protected function beforeInsert(array $data): array
    {
        return $this->calculateTotal($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->calculateTotal($data);
    }

    protected function calculateTotal(array $data): array
    {
        if (isset($data['data']['quantity']) && isset($data['data']['unit_price'])) {
            $data['data']['total'] = $data['data']['quantity'] * $data['data']['unit_price'];
        }
        return $data;
    }
}

