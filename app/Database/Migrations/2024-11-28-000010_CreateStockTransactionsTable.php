<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockTransactionsTable extends Migration
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
            'part_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'job_card_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Job ID if stock was used for repair',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['in', 'out', 'adjustment'],
                'comment'    => 'in=restock, out=used, adjustment=manual correction',
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'comment'    => 'Positive for in, negative for out',
            ],
            'quantity_before' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'quantity_after' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'PO number, invoice number, etc.',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('part_id');
        $this->forge->addKey('job_card_id');
        $this->forge->addKey('type');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('part_id', 'parts_inventory', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('job_card_id', 'job_cards', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('stock_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('stock_transactions');
    }
}

