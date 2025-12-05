<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserPreferencesAndLoginHistory extends Migration
{
    public function up()
    {
        // Add theme_preference to users table
        $this->forge->addColumn('users', [
            'theme_preference' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => 'light',
                'null' => false,
                'after' => 'branch_id'
            ],
            'language_preference' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
                'default' => 'th',
                'null' => false,
                'after' => 'theme_preference'
            ]
        ]);

        // Create login_history table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'device_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'browser' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'platform' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'login_at' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['success', 'failed'],
                'default' => 'success'
            ],
            'failure_reason' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('login_at');
        $this->forge->createTable('login_history', true);
    }

    public function down()
    {
        // Drop columns from users
        $this->forge->dropColumn('users', 'theme_preference');
        $this->forge->dropColumn('users', 'language_preference');

        // Drop login_history table
        $this->forge->dropTable('login_history', true);
    }
}
