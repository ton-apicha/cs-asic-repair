<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DebugUser extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:user';
    protected $description = 'Debug user session and database data';

    public function run(array $params)
    {
        $username = $params[0] ?? 'superadmin';

        $userModel = model('UserModel');
        $user = $userModel->where('username', $username)->first();

        if (!$user) {
            CLI::error("User '{$username}' not found!");
            return;
        }

        CLI::write("=== User Database Record ===", 'green');
        CLI::write("ID: " . $user['id']);
        CLI::write("Username: " . $user['username']);
        CLI::write("Role: " . ($user['role'] ?? 'NULL'));
        CLI::write("Branch ID: " . ($user['branch_id'] ?? 'NULL'));
        CLI::write("Is Active: " . ($user['is_active'] ?? 'NULL'));
        CLI::write("Email: " . ($user['email'] ?? 'NULL'));

        CLI::newLine();
        CLI::write("=== Role Checks ===", 'green');

        $role = $user['role'] ?? null;
        CLI::write("Role value: " . var_export($role, true));
        CLI::write("Is 'super_admin': " . ($role === 'super_admin' ? 'YES' : 'NO'));
        CLI::write("Is 'admin': " . ($role === 'admin' ? 'YES' : 'NO'));
        CLI::write("In ['admin', 'super_admin']: " . (in_array($role, ['admin', 'super_admin']) ? 'YES' : 'NO'));

        CLI::newLine();
        CLI::write("=== Expected Session Data ===", 'green');
        CLI::write("userId: " . $user['id']);
        CLI::write("username: " . $user['username']);
        CLI::write("role: " . ($user['role'] ?? 'NULL'));
        CLI::write("branchId: " . ($user['branch_id'] ?? 'NULL'));

        CLI::newLine();
        CLI::write("=== Menu Visibility ===", 'green');
        $isAdmin = in_array($role, ['admin', 'super_admin']);
        $isSuperAdmin = $role === 'super_admin';

        CLI::write("Should see Quotations: " . ($isAdmin ? 'YES' : 'NO'));
        CLI::write("Should see Reports: " . ($isAdmin ? 'YES' : 'NO'));
        CLI::write("Should see Settings: " . ($isAdmin ? 'YES' : 'NO'));
        CLI::write("Should see System Monitor: " . ($isSuperAdmin ? 'YES' : 'NO'));
        CLI::write("Should see All Branches selector: " . ($isSuperAdmin ? 'YES' : 'NO'));
    }
}
