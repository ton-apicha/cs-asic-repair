<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobCardsTable extends Migration
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
            'job_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'Format: YYMMDDXXX (e.g., 251127001)',
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'asset_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'technician_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Assigned technician',
            ],
            'symptom' => [
                'type' => 'TEXT',
                'comment' => 'Initial symptoms reported',
            ],
            'diagnosis' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Technician diagnosis',
            ],
            'solution' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Repair solution/work done',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['new_checkin', 'pending_repair', 'in_progress', 'repair_done', 'ready_handover', 'delivered', 'cancelled'],
                'default'    => 'new_checkin',
            ],
            'is_warranty_claim' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'original_job_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Reference to original job for warranty claims',
            ],
            'labor_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'parts_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'total_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'vat_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'grand_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'amount_paid' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'checkin_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'repair_start_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'repair_end_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'delivery_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'cancel_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_locked' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'Lock job after delivery to prevent edits',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addUniqueKey('job_id');
        $this->forge->addKey('customer_id');
        $this->forge->addKey('asset_id');
        $this->forge->addKey('branch_id');
        $this->forge->addKey('technician_id');
        $this->forge->addKey('status');
        $this->forge->addKey('checkin_date');
        $this->forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('asset_id', 'assets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('technician_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('job_cards');
    }

    public function down()
    {
        $this->forge->dropTable('job_cards');
    }
}

