<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class JobCardModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testGenerateJobId()
    {
        $model = new JobCardModel();
        
        // Mock data
        $data = [
            'customer_id' => 1,
            'asset_id'    => 1,
            'symptom'     => 'Test Symptom',
        ];

        // Insert should trigger generateJobId
        $model->insert($data);
        $jobId = $model->getInsertID();
        $job = $model->find($jobId);

        $this->assertNotNull($job['job_id']);
        // Format YYMMDDXXX
        $this->assertMatchesRegularExpression('/^\d{6}\d{3}$/', $job['job_id']);
    }

    public function testCalculateTotals()
    {
        $model = new JobCardModel();
        
        // Create a job
        $jobId = $model->insert([
            'customer_id' => 1,
            'asset_id'    => 1,
            'symptom'     => 'Test',
            'labor_cost'  => 1000,
        ]);

        // Mock parts cost (since we don't have JobPartsModel mocked easily here, 
        // we might need to rely on the model's calculation logic or mock the dependency if possible.
        // For integration test, we should insert parts.)
        
        // Let's manually set parts_cost for now to test the calculation logic in update
        // But wait, calculateTotals fetches from DB. 
        // So we need to insert into job_parts table.
        
        $db = \Config\Database::connect();
        
        // Create a part
        $db->table('parts_inventory')->insert([
            'name' => 'Test Part',
            'part_code' => 'P001',
            'cost_price' => 100,
            'sell_price' => 200,
            'quantity' => 10
        ]);
        $partId = $db->insertID();

        // Add part to job
        $db->table('job_parts')->insert([
            'job_card_id' => $jobId,
            'part_id' => $partId,
            'quantity' => 2,
            'unit_price' => 200,
            'subtotal' => 400,
            'created_by' => 1
        ]);

        // Mock Settings for VAT
        $db->table('settings')->insertBatch([
            ['key' => 'vat_type', 'value' => 'inclusive'],
            ['key' => 'vat_rate', 'value' => '7'],
        ]);

        // Run calculation
        $totals = $model->calculateTotals($jobId);

        // Labor 1000 + Parts 400 = 1400
        // VAT Inclusive: 1400 * 0.07 = 98
        // Grand Total = 1498
        
        $this->assertEquals(1000, $totals['labor_cost']);
        $this->assertEquals(400, $totals['parts_cost']);
        $this->assertEquals(1400, $totals['total_cost']);
        $this->assertEquals(98, $totals['vat_amount']);
        $this->assertEquals(1498, $totals['grand_total']);
    }

    public function testStatusChangeUpdatesTimestamps()
    {
        $model = new JobCardModel();
        $jobId = $model->insert([
            'customer_id' => 1,
            'asset_id'    => 1,
            'symptom'     => 'Test',
            'status'      => JobCardModel::STATUS_NEW_CHECKIN
        ]);

        // Change to In Progress
        $model->update($jobId, ['status' => JobCardModel::STATUS_IN_PROGRESS]);
        $job = $model->find($jobId);
        $this->assertNotNull($job['repair_start_date']);

        // Change to Repair Done
        $model->update($jobId, ['status' => JobCardModel::STATUS_REPAIR_DONE]);
        $job = $model->find($jobId);
        $this->assertNotNull($job['repair_end_date']);
    }
}
