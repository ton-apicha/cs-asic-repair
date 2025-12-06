<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Add is_active column to customers table
 * This column tracks whether a customer account is active or inactive
 */
class AddIsActiveToCustomers extends Migration
{
    public function up()
    {
        // Check if column already exists
        if ($this->db->fieldExists('is_active', 'customers')) {
            return; // Column already exists, skip
        }

        $fields = [
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
                'after'      => 'vip_status',
            ],
        ];

        $this->forge->addColumn('customers', $fields);
    }

    public function down()
    {
        if ($this->db->fieldExists('is_active', 'customers')) {
            $this->forge->dropColumn('customers', 'is_active');
        }
    }
}
