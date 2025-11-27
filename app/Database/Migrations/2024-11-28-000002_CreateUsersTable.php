<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
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
                'constraint' => ['admin', 'technician'],
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

        // Insert default admin user (password: admin123)
        $this->db->table('users')->insert([
            'branch_id'  => 1,
            'username'   => 'admin',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'name'       => 'Administrator',
            'email'      => 'admin@example.com',
            'role'       => 'admin',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Insert default technician user (password: tech123)
        $this->db->table('users')->insert([
            'branch_id'  => 1,
            'username'   => 'technician',
            'password'   => password_hash('tech123', PASSWORD_DEFAULT),
            'name'       => 'Default Technician',
            'email'      => 'tech@example.com',
            'role'       => 'technician',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}

