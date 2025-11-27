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
            'title'    => lang('App.systemSettings'),
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
        ];

        foreach ($settings as $key) {
            $value = $this->request->getPost($key);
            if ($value !== null) {
                $this->settingModel->set($key, $value);
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
            'title'    => lang('App.branches'),
            'branches' => $branches,
        ]));
    }

    /**
     * Store new branch
     */
    public function storeBranch()
    {
        $branchModel = new BranchModel();

        $data = [
            'name'    => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'phone'   => $this->request->getPost('phone'),
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
     */
    public function updateBranch(int $id)
    {
        $branchModel = new BranchModel();

        $data = [
            'name'      => $this->request->getPost('name'),
            'address'   => $this->request->getPost('address'),
            'phone'     => $this->request->getPost('phone'),
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
     */
    public function deleteBranch(int $id)
    {
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
        
        $users = $userModel->select('users.*, branches.name as branch_name')
            ->join('branches', 'branches.id = users.branch_id', 'left')
            ->findAll();

        return view('settings/users', $this->getViewData([
            'title'    => lang('App.users'),
            'users'    => $users,
            'branches' => $branchModel->getActive(),
        ]));
    }

    /**
     * Create user form
     */
    public function createUser(): string
    {
        $branchModel = new BranchModel();

        return view('settings/user_form', $this->getViewData([
            'title'    => lang('App.newUser'),
            'user'     => null,
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
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'name'     => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'branch_id' => $this->request->getPost('branch_id'),
            'username'  => $this->request->getPost('username'),
            'password'  => $this->request->getPost('password'),
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'role'      => $this->request->getPost('role'),
            'is_active' => 1,
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('/settings/users')
                ->with('success', lang('App.userCreated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', lang('App.operationFailed'));
    }

    /**
     * Edit user form
     */
    public function editUser(int $id): string
    {
        $userModel = new UserModel();
        $branchModel = new BranchModel();

        $user = $userModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('settings/user_form', $this->getViewData([
            'title'    => lang('App.editUser'),
            'user'     => $user,
            'branches' => $branchModel->getActive(),
        ]));
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

        $data = [
            'branch_id' => $this->request->getPost('branch_id'),
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        if ($userModel->update($id, $data)) {
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

        if ($userModel->delete($id)) {
            return redirect()->to('/settings/users')
                ->with('success', lang('App.userDeleted'));
        }

        return redirect()->to('/settings/users')
            ->with('error', lang('App.operationFailed'));
    }
}

