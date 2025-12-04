<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Fix users table role ENUM to include super_admin
 */
class FixUsersRoleEnum extends Migration
{
    public function up()
    {
        // Modify role column to include super_admin in ENUM
        $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'technician') NULL";
        $this->db->query($sql);

        // Update any existing users with empty role to NULL
        $this->db->table('users')
            ->where('role', '')
            ->orWhere('role IS NULL')
            ->update(['role' => NULL]);
    }

    public function down()
    {
        // Revert to old ENUM (without super_admin)
        $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'technician') NULL";
        $this->db->query($sql);
    }
}
