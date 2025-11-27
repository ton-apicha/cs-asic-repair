<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssetsTable extends Migration
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
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'brand_model' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'e.g., Bitmain Antminer S19 Pro, Canaan Avalon 1246',
            ],
            'serial_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'Unique identifier for the ASIC miner',
            ],
            'mac_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'hash_rate' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Hash rate in TH/s',
            ],
            'external_condition' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'External condition notes (scratches, stickers, etc.)',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['stored', 'repairing', 'repaired', 'returned'],
                'default'    => 'stored',
                'comment'    => 'stored=ฝากเครื่อง, repairing=รอซ่อม, repaired=ซ่อมเสร็จ, returned=ส่งคืนแล้ว',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('serial_number');
        $this->forge->addKey('customer_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assets');
    }

    public function down()
    {
        $this->forge->dropTable('assets');
    }
}

