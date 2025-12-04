<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Create system_metrics table for storing historical monitoring data
 */
class CreateSystemMetricsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'metric_type' => [
                'type' => 'ENUM',
                'constraint' => ['cpu', 'ram', 'disk', 'network'],
                'null' => false,
            ],
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'details' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'recorded_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['metric_type', 'recorded_at']);
        $this->forge->createTable('system_metrics');
    }

    public function down()
    {
        $this->forge->dropTable('system_metrics');
    }
}
