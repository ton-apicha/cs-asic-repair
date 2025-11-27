<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobIdCounterTable extends Migration
{
    public function up()
    {
        // Table to track monthly job ID counter for format YYMMDDXXX
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'year_month' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'comment'    => 'Format: YYMM (e.g., 2511 for November 2025)',
            ],
            'last_number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Last used number for this month',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('year_month');
        $this->forge->createTable('job_id_counter');
    }

    public function down()
    {
        $this->forge->dropTable('job_id_counter');
    }
}

