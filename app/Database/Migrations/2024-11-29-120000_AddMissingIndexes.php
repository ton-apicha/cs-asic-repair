<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Add Missing Database Indexes for Performance Optimization
 * 
 * Based on Security & Quality Audit Issue #15
 * These indexes improve query performance for frequently filtered columns.
 */
class AddMissingIndexes extends Migration
{
    public function up()
    {
        // Add index for parts_inventory.branch_id (separate from unique key)
        // This improves performance for queries filtering by branch only
        if (!$this->db->indexExists('parts_inventory', ['branch_id'])) {
            $this->forge->addKey('branch_id');
            $this->forge->processIndexes('parts_inventory');
        }

        // Note: Other indexes already exist in their respective migrations:
        // - job_cards.status (line 162 in CreateJobCardsTable)
        // - job_cards.technician_id (line 161 in CreateJobCardsTable)
        // - job_cards.branch_id (line 160 in CreateJobCardsTable)
        // - customers.phone (line 60 in CreateCustomersTable)
        // - customers.name (line 61 in CreateCustomersTable)
        // - assets.serial_number (unique, line 74 in CreateAssetsTable)
        // - assets.branch_id (added in 2024-11-29-000002_AddBranchIdToAssets)
    }

    public function down()
    {
        // Remove the added index
        if ($this->db->indexExists('parts_inventory', ['branch_id'])) {
            $this->forge->dropKey('parts_inventory', 'branch_id');
        }
    }
}

