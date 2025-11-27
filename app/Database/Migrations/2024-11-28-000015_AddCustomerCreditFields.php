<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerCreditFields extends Migration
{
    public function up()
    {
        // Add credit fields to customers table
        $fields = [
            'credit_limit' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
                'null'       => false,
                'after'      => 'vip_status',
            ],
            'credit_used' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
                'null'       => false,
                'after'      => 'credit_limit',
            ],
            'credit_terms' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 30,
                'null'       => false,
                'after'      => 'credit_used',
                'comment'    => 'Payment terms in days',
            ],
        ];

        $this->forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customers', ['credit_limit', 'credit_used', 'credit_terms']);
    }
}

