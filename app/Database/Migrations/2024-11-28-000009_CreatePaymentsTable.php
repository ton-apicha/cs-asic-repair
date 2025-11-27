<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'job_card_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'transfer'],
                'default'    => 'cash',
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'reference_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'Transaction ID for bank transfer/QR payment',
            ],
            'payment_date' => [
                'type' => 'DATETIME',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'received_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('job_card_id');
        $this->forge->addKey('payment_date');
        $this->forge->addForeignKey('job_card_id', 'job_cards', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('received_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}

