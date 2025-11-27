<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AuditLogModel;

/**
 * Authentication Controller
 * 
 * Handles user login, logout, and session management.
 */
class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display login page
     *
     * @return string
     */
    public function login(): string
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard')->send();
        }

        return view('auth/login', [
            'title' => lang('App.login'),
        ]);
    }

    /**
     * Process login attempt
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function attemptLogin()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Find user by username
        $user = $this->userModel->where('username', $username)
            ->where('is_active', 1)
            ->first();

        if (! $user) {
            return redirect()->back()
                ->withInput()
                ->with('error', lang('App.invalidCredentials'));
        }

        // Verify password
        if (! password_verify($password, $user['password'])) {
            // Log failed login attempt
            $this->logFailedLogin($username);
            return redirect()->back()
                ->withInput()
                ->with('error', lang('App.invalidCredentials'));
        }

        // Set session data
        $sessionData = [
            'userId'     => $user['id'],
            'username'   => $user['username'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'branchId'   => $user['branch_id'],
            'isLoggedIn' => true,
        ];
        $this->session->set($sessionData);

        // Update last login
        $this->userModel->update($user['id'], [
            'last_login' => date('Y-m-d H:i:s'),
        ]);

        // Log successful login
        $this->logSuccessfulLogin($user['id']);

        // Redirect to intended URL or dashboard
        $redirectUrl = $this->session->get('redirect_url') ?? '/dashboard';
        $this->session->remove('redirect_url');

        return redirect()->to($redirectUrl)
            ->with('success', lang('App.welcomeBack', ['name' => $user['name']]));
    }

    /**
     * Logout user
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        // Log logout action
        if ($this->session->get('isLoggedIn')) {
            $this->logLogout($this->session->get('userId'));
        }

        // Destroy session
        $this->session->destroy();

        return redirect()->to('/login')
            ->with('success', lang('App.loggedOut'));
    }

    /**
     * Log failed login attempt
     *
     * @param string $username
     */
    private function logFailedLogin(string $username): void
    {
        $auditModel = new AuditLogModel();
        $auditModel->insert([
            'user_id'    => null,
            'action'     => 'LOGIN_FAILED',
            'table_name' => 'users',
            'record_id'  => null,
            'old_values' => null,
            'new_values' => json_encode(['username' => $username, 'ip' => $this->request->getIPAddress()]),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
        ]);
    }

    /**
     * Log successful login
     *
     * @param int $userId
     */
    private function logSuccessfulLogin(int $userId): void
    {
        $auditModel = new AuditLogModel();
        $auditModel->insert([
            'user_id'    => $userId,
            'action'     => 'LOGIN',
            'table_name' => 'users',
            'record_id'  => $userId,
            'old_values' => null,
            'new_values' => json_encode(['ip' => $this->request->getIPAddress()]),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
        ]);
    }

    /**
     * Log logout action
     *
     * @param int $userId
     */
    private function logLogout(int $userId): void
    {
        $auditModel = new AuditLogModel();
        $auditModel->insert([
            'user_id'    => $userId,
            'action'     => 'LOGOUT',
            'table_name' => 'users',
            'record_id'  => $userId,
            'old_values' => null,
            'new_values' => null,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
        ]);
    }
}
