<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Enterprise Dashboard Controller
 * Consolidated view for all enterprise features
 */
class EnterpriseDashboardController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isSuperAdmin()) {
            return redirect()->to('/dashboard')
                ->with('error', lang('App.accessDenied'));
        }

        $data = [
            'title' => 'Enterprise Dashboard',
        ];

        return view('admin/enterprise/index', $this->getViewData($data));
    }
}
