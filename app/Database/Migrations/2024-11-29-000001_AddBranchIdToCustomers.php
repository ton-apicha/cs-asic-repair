<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBranchIdToCustomers extends Migration
{
    public function up()
    {
        // Check if column already exists
        if ($this->db->fieldExists('branch_id', 'customers')) {
            return; // Column already exists, skip
        }
        
        $driver = $this->db->DBDriver;
        
        if ($driver === 'SQLite3') {
            // SQLite: Simply add the column
            $this->forge->addColumn('customers', [
                'branch_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
            ]);
        } else {
            // MySQL/MariaDB: Add column with FK
            $this->forge->addColumn('customers', [
                'branch_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'id',
                    'comment'    => 'NULL = visible to all branches (Super Admin created)',
                ],
            ]);
            
            // Add foreign key
            $this->db->query('ALTER TABLE `customers` ADD CONSTRAINT `customers_branch_id_fk` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
            
            // Add index
            $this->db->query('CREATE INDEX `customers_branch_id_idx` ON `customers` (`branch_id`)');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('branch_id', 'customers')) {
            return;
        }
        
        $driver = $this->db->DBDriver;
        
        if ($driver !== 'SQLite3') {
            // Remove foreign key first (MySQL)
            $this->db->query('ALTER TABLE `customers` DROP FOREIGN KEY `customers_branch_id_fk`');
            $this->db->query('DROP INDEX `customers_branch_id_idx` ON `customers`');
        }
        
        $this->forge->dropColumn('customers', 'branch_id');
    }
}
