<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBranchIdToAssets extends Migration
{
    public function up()
    {
        // Check if column already exists
        if ($this->db->fieldExists('branch_id', 'assets')) {
            return; // Column already exists, skip
        }
        
        $driver = $this->db->DBDriver;
        
        if ($driver === 'SQLite3') {
            // SQLite: Simply add the column
            $this->forge->addColumn('assets', [
                'branch_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ]);
        } else {
            // MySQL/MariaDB: Add column with FK
            $this->forge->addColumn('assets', [
                'branch_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'customer_id',
                    'comment'    => 'Branch where the asset is physically located',
                ],
            ]);
            
            // Add foreign key
            $this->db->query('ALTER TABLE `assets` ADD CONSTRAINT `assets_branch_id_fk` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
            
            // Add index
            $this->db->query('CREATE INDEX `assets_branch_id_idx` ON `assets` (`branch_id`)');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('branch_id', 'assets')) {
            return;
        }
        
        $driver = $this->db->DBDriver;
        
        if ($driver !== 'SQLite3') {
            // Remove foreign key first (MySQL)
            $this->db->query('ALTER TABLE `assets` DROP FOREIGN KEY `assets_branch_id_fk`');
            $this->db->query('DROP INDEX `assets_branch_id_idx` ON `assets`');
        }
        
        $this->forge->dropColumn('assets', 'branch_id');
    }
}
