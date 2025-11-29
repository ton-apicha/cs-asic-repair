<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Add 'super_admin' role to users table ENUM
 * 
 * Role logic:
 * - role='super_admin' → Super Admin (can see all branches, branch_id must be NULL)
 * - role='admin' → Branch Admin (can only see their branch, branch_id required)
 * - role='technician' → Technician (can only see assigned jobs in their branch)
 */
class AddSuperAdminRole extends Migration
{
    public function up()
    {
        $driver = $this->db->DBDriver;
        
        if ($driver === 'SQLite3') {
            // SQLite: CodeIgniter Forge creates CHECK constraint for ENUM
            // We need to drop and recreate the constraint
            
            // Step 1: Drop the old CHECK constraint by recreating the table
            // First, create a backup table
            $this->db->query("CREATE TABLE users_backup AS SELECT * FROM users");
            
            // Step 2: Drop the old table
            $this->forge->dropTable('users', true);
            
            // Step 3: Recreate table with new ENUM values
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
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
                'role' => [
                    'type'       => 'ENUM',
                    'constraint' => ['admin', 'technician', 'super_admin'],
                    'default'    => 'technician',
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
                'last_login' => [
                    'type' => 'DATETIME',
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
            ]);
            
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('username');
            $this->forge->addForeignKey('branch_id', 'branches', 'id', 'SET NULL', 'CASCADE');
            $this->forge->createTable('users');
            
            // Step 4: Copy data back
            $this->db->query("INSERT INTO users SELECT * FROM users_backup");
            
            // Step 5: Drop backup table
            $this->forge->dropTable('users_backup', true);
        } else {
            // MySQL/MariaDB: Alter ENUM to include 'super_admin'
            $this->db->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'technician', 'super_admin') DEFAULT 'technician'");
        }
    }

    public function down()
    {
        $driver = $this->db->DBDriver;
        
        if ($driver === 'SQLite3') {
            // SQLite: Nothing to revert (no ENUM constraint)
        } else {
            // MySQL/MariaDB: Revert ENUM to original values
            // First, update any super_admin users to admin
            $this->db->query("UPDATE `users` SET `role` = 'admin' WHERE `role` = 'super_admin'");
            
            // Then alter ENUM back
            $this->db->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'technician') DEFAULT 'technician'");
        }
    }
}
