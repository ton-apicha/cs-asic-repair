<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePartsInventoryTable extends Migration
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
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'NULL = Central warehouse, otherwise branch-specific',
            ],
            'part_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'serial_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'Optional S/N for high-value parts',
            ],
            'cost_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'sell_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'reorder_point' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 5,
                'comment'    => 'Alert when stock falls below this level',
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'Physical location (e.g., Shelf A1, Warehouse 2)',
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addUniqueKey(['part_code', 'branch_id']);
        $this->forge->addKey('name');
        $this->forge->addKey('category');
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('parts_inventory');

        // Insert sample parts
        $parts = [
            ['part_code' => 'BM1398', 'name' => 'BM1398 ASIC Chip', 'cost_price' => 150, 'sell_price' => 250, 'quantity' => 50, 'category' => 'Chips'],
            ['part_code' => 'FAN-12038', 'name' => 'Cooling Fan 12038', 'cost_price' => 80, 'sell_price' => 150, 'quantity' => 30, 'category' => 'Fans'],
            ['part_code' => 'PSU-APW12', 'name' => 'APW12 Power Supply Unit', 'cost_price' => 2500, 'sell_price' => 3500, 'quantity' => 10, 'category' => 'PSU'],
            ['part_code' => 'CB-S19', 'name' => 'S19 Control Board', 'cost_price' => 1500, 'sell_price' => 2500, 'quantity' => 5, 'category' => 'Control Board'],
            ['part_code' => 'HB-S19PRO', 'name' => 'S19 Pro Hashboard', 'cost_price' => 5000, 'sell_price' => 7500, 'quantity' => 3, 'category' => 'Hashboard'],
            ['part_code' => 'HEATSINK-S19', 'name' => 'S19 Heatsink', 'cost_price' => 200, 'sell_price' => 400, 'quantity' => 20, 'category' => 'Cooling'],
            ['part_code' => 'THERMAL-PASTE', 'name' => 'Thermal Paste (Tube)', 'cost_price' => 50, 'sell_price' => 100, 'quantity' => 100, 'category' => 'Consumables'],
        ];

        foreach ($parts as $part) {
            $part['branch_id'] = null; // Central warehouse
            $part['reorder_point'] = 5;
            $part['created_at'] = date('Y-m-d H:i:s');
            $part['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('parts_inventory')->insert($part);
        }
    }

    public function down()
    {
        $this->forge->dropTable('parts_inventory');
    }
}

