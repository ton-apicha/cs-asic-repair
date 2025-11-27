<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
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
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'string',
                'comment'    => 'string, number, boolean, json',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('key');
        $this->forge->createTable('settings');

        // Insert default settings
        $settings = [
            [
                'key'         => 'company_name',
                'value'       => 'ASIC Repair Center',
                'type'        => 'string',
                'description' => 'Company name displayed on documents',
            ],
            [
                'key'         => 'company_address',
                'value'       => '123 Mining Street, Bangkok 10100',
                'type'        => 'string',
                'description' => 'Company address for documents',
            ],
            [
                'key'         => 'company_phone',
                'value'       => '02-xxx-xxxx',
                'type'        => 'string',
                'description' => 'Company phone number',
            ],
            [
                'key'         => 'company_email',
                'value'       => 'info@asicrepair.com',
                'type'        => 'string',
                'description' => 'Company email address',
            ],
            [
                'key'         => 'company_tax_id',
                'value'       => '',
                'type'        => 'string',
                'description' => 'Company tax ID for invoices',
            ],
            [
                'key'         => 'vat_type',
                'value'       => 'inclusive',
                'type'        => 'string',
                'description' => 'VAT type: inclusive (7%) or none',
            ],
            [
                'key'         => 'vat_rate',
                'value'       => '7',
                'type'        => 'number',
                'description' => 'VAT rate percentage',
            ],
            [
                'key'         => 'currency',
                'value'       => 'THB',
                'type'        => 'string',
                'description' => 'Default currency',
            ],
            [
                'key'         => 'warranty_days',
                'value'       => '30',
                'type'        => 'number',
                'description' => 'Default warranty period in days',
            ],
            [
                'key'         => 'job_id_prefix',
                'value'       => '',
                'type'        => 'string',
                'description' => 'Prefix for job IDs (optional)',
            ],
            [
                'key'         => 'default_language',
                'value'       => 'th',
                'type'        => 'string',
                'description' => 'Default system language',
            ],
        ];

        foreach ($settings as $setting) {
            $setting['created_at'] = date('Y-m-d H:i:s');
            $setting['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('settings')->insert($setting);
        }
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}

