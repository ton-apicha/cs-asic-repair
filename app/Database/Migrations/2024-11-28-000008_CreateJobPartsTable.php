<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobPartsTable extends Migration
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
            'part_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'Price at time of use (snapshot)',
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'serial_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'S/N of specific part used (if applicable)',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'added_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'is_deducted' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'Whether stock has been deducted',
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
        $this->forge->addKey('part_id');
        $this->forge->addForeignKey('job_card_id', 'job_cards', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('part_id', 'parts_inventory', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('added_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('job_parts');
    }

    public function down()
    {
        $this->forge->dropTable('job_parts');
    }
}

