<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserModel;

class CreateSuperAdmin extends BaseCommand
{
    protected $group       = 'User';
    protected $name        = 'user:create-superadmin';
    protected $description = 'Create a Super Admin user';

    public function run(array $params)
    {
        $userModel = new UserModel();
        $db = \Config\Database::connect();

        // Check if superadmin already exists
        $existing = $userModel->where('username', 'superadmin')->first();
        
        $data = [
            'username'  => 'superadmin',
            'password'  => password_hash('super123', PASSWORD_DEFAULT),
            'name'      => 'Super Administrator',
            'email'     => 'super@example.com',
            'role'      => 'super_admin',
            'branch_id' => null,
            'is_active' => 1,
        ];
        
        if ($existing) {
            // Update existing Super Admin
            $db->table('users')
                ->where('username', 'superadmin')
                ->update([
                    'password' => $data['password'],
                    'is_active' => 1,
                    'role' => 'super_admin',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            
            CLI::write('✅ Super Admin password reset successfully!', 'green');
            CLI::write('Username: superadmin', 'cyan');
            CLI::write('Password: super123', 'cyan');
            return;
        }
        
        // Insert new Super Admin
        $db->table('users')->insert(array_merge($data, [
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]));
        
        CLI::write('✅ Super Admin created successfully!', 'green');
        CLI::write('Username: superadmin', 'cyan');
        CLI::write('Password: super123', 'cyan');
    }
}

