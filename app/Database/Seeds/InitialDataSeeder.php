<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Note: Default branch and users are already created in migrations
        // This seeder only adds additional default settings and common symptoms

        // Create default settings (check if not exists)
        $settings = [
            ['key' => 'vat_type', 'value' => 'inclusive', 'description' => 'VAT calculation type (inclusive/exclusive/none)'],
            ['key' => 'vat_rate', 'value' => '7', 'description' => 'VAT percentage rate'],
            ['key' => 'job_prefix', 'value' => '', 'description' => 'Job ID prefix'],
            ['key' => 'company_name', 'value' => 'ASIC Repair Center', 'description' => 'Company name'],
            ['key' => 'company_address', 'value' => 'Bangkok, Thailand', 'description' => 'Company address'],
            ['key' => 'company_phone', 'value' => '02-123-4567', 'description' => 'Company phone'],
            ['key' => 'company_email', 'value' => 'info@asicrepair.com', 'description' => 'Company email'],
        ];

        foreach ($settings as $setting) {
            // Check if setting exists
            $exists = $this->db->table('settings')
                ->where('key', $setting['key'])
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('settings')->insert([
                    'key'         => $setting['key'],
                    'value'       => $setting['value'],
                    'description' => $setting['description'],
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Create some common symptom histories
        $symptoms = [
            'No Hash Rate',
            'Low Hash Rate',
            'High Temperature',
            'Fan Not Working',
            'Power Issue',
            'Board Not Detected',
            'Chip Failure',
            'Network Error',
            'Overheating',
            'Strange Noise',
        ];

        foreach ($symptoms as $symptom) {
            // Check if symptom exists
            $exists = $this->db->table('symptom_history')
                ->where('symptom', $symptom)
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('symptom_history')->insert([
                    'symptom'    => $symptom,
                    'frequency'  => rand(1, 50),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        echo "Initial data seeded successfully!\n";
        echo "\n";
        echo "Default Login Credentials:\n";
        echo "===========================\n";
        echo "Admin:\n";
        echo "  Username: admin\n";
        echo "  Password: admin123\n";
        echo "\n";
        echo "Technician:\n";
        echo "  Username: technician\n";
        echo "  Password: tech123\n";
    }
}
