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

        // Check if superadmin already exists
        $existing = $userModel->where('username', 'superadmin')->first();
        if ($existing) {
            CLI::write('Super Admin already exists!', 'yellow');
            return;
        }

        $data = [
            'username'  => 'superadmin',
            'password'  => password_hash('super123', PASSWORD_DEFAULT),
            'name'      => 'Super Administrator',
            'email'     => 'super@example.com',
            'role'      => 'super_admin',
            'branch_id' => null,
            'is_active' => 1,
        ];

        // Skip validation and audit for CLI command
        $userModel->skipValidation(true);
        
        // Insert directly using query builder to bypass audit
        $db = \Config\Database::connect();
        $db->table('users')->insert($data);
        
        CLI::write('✅ Super Admin created successfully!', 'green');
        CLI::write('Username: superadmin', 'cyan');
        CLI::write('Password: super123', 'cyan');
    }
}

