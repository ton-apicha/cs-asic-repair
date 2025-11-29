<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class PartsInventoryModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    public function testDeductStock()
    {
        $model = new PartsInventoryModel();

        // Create a part
        $partId = $model->insert([
            'name' => 'Test Part',
            'part_code' => 'P001',
            'cost_price' => 100,
            'sell_price' => 200,
            'quantity' => 10,
            'branch_id' => 1
        ]);

        // Deduct stock
        $model->deductStock($partId, 2, 999, 1); // 999 is dummy job id, 1 is dummy user id

        $part = $model->find($partId);
        $this->assertEquals(8, $part['quantity']);

        // Verify transaction log
        $txnModel = new StockTransactionModel();
        $txn = $txnModel->where('part_id', $partId)->first();
        $this->assertNotNull($txn);
        $this->assertEquals('out', $txn['type']);
        $this->assertEquals(2, $txn['quantity']);
        $this->assertEquals(8, $txn['balance_after']);
    }

    public function testGetLowStock()
    {
        $model = new PartsInventoryModel();

        // Create normal part
        $model->insert([
            'name' => 'Normal Part',
            'part_code' => 'P001',
            'quantity' => 10,
            'reorder_point' => 5
        ]);

        // Create low stock part
        $model->insert([
            'name' => 'Low Stock Part',
            'part_code' => 'P002',
            'quantity' => 2,
            'reorder_point' => 5
        ]);

        $lowStock = $model->getLowStock();
        $this->assertCount(1, $lowStock);
        $this->assertEquals('Low Stock Part', $lowStock[0]['name']);
    }
}
