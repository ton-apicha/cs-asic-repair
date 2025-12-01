<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\BranchModel;
use App\Models\UserModel;

/**
 * Settings Controller
 * 
 * Manages system settings, branches, and users.
 * Admin only access.
 */
class SettingController extends BaseController
{
    protected SettingModel $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    /**
     * System settings page
     */
    public function index(): string
    {
        $settings = $this->settingModel->getAll();

        return view('settings/index', $this->getViewData([
            'title' => lang('App.systemSettings'),
            'settings' => $settings,
        ]));
    }

    /**
     * Update settings
     */
    public function update()
    {
        $settings = [
            'company_name',
            'company_address',
            'company_phone',
            'company_email',
            'company_tax_id',
            'vat_type',
            'vat_rate',
            'warranty_days',
            'job_id_prefix',
            'currency',
            'default_language',
        ];

        foreach ($settings as $key) {
            $value = $this->request->getPost($key);
            if ($value !== null) {
                $this->settingModel->setValue($key, $value);
            }
        }

        return redirect()->to('/settings')
            ->with('success', lang('App.settingsSaved'));
    }

    // ========================================================================
    // Branch Management
    // ========================================================================

    /**
     * List branches
     */
    public function branches(): string
    {
        $branchModel = new BranchModel();
        $branches = $branchModel->findAll();

        return view('settings/branches', $this->getViewData([
            'title' => lang('App.branches'),
            'branches' => $branches,
        ]));
    }

    /**
     * Store new branch
     * Super Admin only
     */
    public function storeBranch()
    {
        // Only Super Admin can create branches
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/settings/branches')
                ->with('error', lang('App.accessDenied'));
        }

        $branchModel = new BranchModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'phone' => $this->request->getPost('phone'),
        ];

        if ($branchModel->insert($data)) {
            return redirect()->to('/settings/branches')
                ->with('success', lang('App.branchCreated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Update branch
     * Super Admin only
     */
    public function updateBranch(int $id)
    {
        // Only Super Admin can update branches
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/settings/branches')
                ->with('error', lang('App.accessDenied'));
        }

        $branchModel = new BranchModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'phone' => $this->request->getPost('phone'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($branchModel->update($id, $data)) {
            return redirect()->to('/settings/branches')
                ->with('success', lang('App.branchUpdated'));
        }

        return redirect()->back()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Delete branch
     * Super Admin only
     */
    public function deleteBranch(int $id)
    {
        // Only Super Admin can delete branches
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/settings/branches')
                ->with('error', lang('App.accessDenied'));
        }

        $branchModel = new BranchModel();

        if ($branchModel->delete($id)) {
            return redirect()->to('/settings/branches')
                ->with('success', lang('App.branchDeleted'));
        }

        return redirect()->to('/settings/branches')
            ->with('error', lang('App.operationFailed'));
    }

    // ========================================================================
    // User Management
    // ========================================================================

    /**
     * List users
     */
    public function users(): string
    {
        $userModel = new UserModel();
        $branchModel = new BranchModel();

        $builder = $userModel->select('users.*, branches.name as branch_name')
            ->join('branches', 'branches.id = users.branch_id', 'left');

        // Filter based on role
        if (!$this->isSuperAdmin()) {
            // Admin: show only users in their branch (exclude super_admin role)
            $builder->where('users.branch_id', $this->getBranchId())
                ->where('users.role !=', 'super_admin');
        }
        // Super Admin: see all users (no filter)

        $users = $builder->findAll();

        return view('settings/users', $this->getViewData([
            'title' => lang('App.users'),
            'users' => $users,
            'branches' => $branchModel->getActive(),
        ]));
    }

    /**
     * Store new user
     */
    public function storeUser()
    {
        $userModel = new UserModel();

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)/]',
            'name' => 'required|min_length[2]|max_length[255]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'phone' => 'permit_empty|regex_match[/^[0-9\\-\\+\\(\\)\\s]{9,20}$/]|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $role = $this->request->getPost('role');
        $branchId = $this->request->getPost('branch_id');

        // Security checks for Admin
        if (!$this->isSuperAdmin()) {
            // Admin CANNOT create super_admin users
            if ($role === 'super_admin') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', lang('App.accessDenied'));
            }

            // Admin can only create users in their own branch
            $branchId = $this->getBranchId();
        }

        // Super Admin must have branch_id = NULL
        if ($role === 'super_admin') {
            $branchId = null;
        }

        $data = [
            'branch_id' => !empty($branchId) ? (int) $branchId : null,
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ];

        $userId = $userModel->createUserWithRole($data, $role, true);

        if ($userId !== false) {
            return redirect()->to('/settings/users')
                ->with('success', lang('App.userCreated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Update user
     */
    public function updateUser(int $id)
    {
        $userModel = new UserModel();

        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/settings/users')
                ->with('error', lang('App.recordNotFound'));
        }

        // Security checks for Admin
        if (!$this->isSuperAdmin()) {
            // Admin CANNOT edit super_admin users
            if ($user['role'] === 'super_admin') {
                return redirect()->to('/settings/users')
                    ->with('error', lang('App.accessDenied'));
            }

            // Admin CANNOT edit users from other branches
            if ($user['branch_id'] !== $this->getBranchId()) {
                return redirect()->to('/settings/users')
                    ->with('error', lang('App.accessDenied'));
            }

            $newRole = $this->request->getPost('role');

            // Admin CANNOT change role to super_admin
            if ($newRole === 'super_admin') {
                return redirect()->back()
                    ->with('error', lang('App.accessDenied'));
            }

            // Force branch_id to be admin's branch (prevent moving users)
            $branchId = $this->getBranchId();
        } else {
            // Super Admin can change branch
            $branchId = $this->request->getPost('branch_id');
            $newRole = $this->request->getPost('role');
        }

        $role = $newRole ?? $this->request->getPost('role');
        $isActive = $this->request->getPost('is_active') ? 1 : 0;

        // Super Admin must have branch_id = NULL
        if ($role === 'super_admin') {
            $branchId = null;
        }

        $data = [
            'branch_id' => !empty($branchId) ? (int) $branchId : null,
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ];

        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        // Update base user data
        $updateSuccess = $userModel->update($id, $data);

        // Update role and active status separately (security: prevent mass assignment)
        if ($updateSuccess) {
            $userModel->setRole($id, $role);
            $userModel->setActiveStatus($id, $isActive);
        }

        if ($updateSuccess) {
            return redirect()->to('/settings/users')
                ->with('success', lang('App.userUpdated'));
        }

        return redirect()->back()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Delete user
     */
    public function deleteUser(int $id)
    {
        $userModel = new UserModel();

        // Prevent self-deletion
        if ($id == $this->getUserId()) {
            return redirect()->to('/settings/users')
                ->with('error', 'Cannot delete your own account');
        }

        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/settings/users')
                ->with('error', lang('App.recordNotFound'));
        }

        // Security checks for Admin
        if (!$this->isSuperAdmin()) {
            // Admin CANNOT delete super_admin users
            if ($user['role'] === 'super_admin') {
                return redirect()->to('/settings/users')
                    ->with('error', lang('App.accessDenied'));
            }

            // Admin CANNOT delete users from other branches
            if ($user['branch_id'] !== $this->getBranchId()) {
                return redirect()->to('/settings/users')
                    ->with('error', lang('App.accessDenied'));
            }
        }

        if ($userModel->delete($id)) {
            return redirect()->to('/settings/users')
                ->with('success', lang('App.userDeleted'));
        }

        return redirect()->to('/settings/users')
            ->with('error', lang('App.operationFailed'));
    }

    // ========================================================================
    // Logo Management
    // ========================================================================

    /**
     * Upload company logo
     */
    public function uploadLogo()
    {
        $file = $this->request->getFile('logo');

        if (!$file || !$file->isValid()) {
            return redirect()->to('/settings')
                ->with('error', lang('App.uploadFailed'));
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return redirect()->to('/settings')
                ->with('error', lang('App.invalidFileType'));
        }

        // Validate file size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            return redirect()->to('/settings')
                ->with('error', lang('App.fileTooLarge'));
        }

        // Delete old logo if exists
        $oldLogo = $this->settingModel->get('company_logo');
        if ($oldLogo && file_exists(FCPATH . 'assets/images/' . $oldLogo)) {
            unlink(FCPATH . 'assets/images/' . $oldLogo);
        }

        // Generate unique filename with MIME-based extension (security)
        $newName = 'logo_' . time() . '.' . $file->guessExtension();

        // Move file to public/assets/images/
        $file->move(FCPATH . 'assets/images/', $newName);

        // Save logo filename in settings
        $this->settingModel->setValue('company_logo', $newName);

        return redirect()->to('/settings')
            ->with('success', lang('App.logoUploaded'));
    }

    /**
     * Delete company logo
     * Admin only
     */
    public function deleteLogo()
    {
        // Admin authorization check
        if (!$this->isAdmin()) {
            return redirect()->to('/settings')
                ->with('error', lang('App.accessDenied'));
        }

        $logo = $this->settingModel->get('company_logo');

        if ($logo && file_exists(FCPATH . 'assets/images/' . $logo)) {
            unlink(FCPATH . 'assets/images/' . $logo);
        }

        $this->settingModel->setValue('company_logo', '');

        return redirect()->to('/settings')
            ->with('success', lang('App.logoDeleted'));
    }
}

