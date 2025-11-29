<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Branch Controller
 * 
 * Handles branch switching for Super Admin
 */
class BranchController extends BaseController
{
    /**
     * Switch the active branch filter for Super Admin
     *
     * @param string $branchId Branch ID or 'all'
     * @return ResponseInterface
     */
    public function switch(string $branchId): ResponseInterface
    {
        // Only Super Admin can switch branches
        if (!$this->isSuperAdmin()) {
            return redirect()->back()->with('error', lang('App.accessDenied'));
        }
        
        if ($branchId === 'all') {
            // Clear branch filter - view all branches
            $this->session->remove('filter_branch_id');
            $this->session->setFlashdata('success', lang('App.allBranches') . ' - ' . lang('App.filterByBranch'));
        } else {
            // Set specific branch filter
            $branchModel = model('BranchModel');
            $branch = $branchModel->find((int)$branchId);
            
            if (!$branch) {
                return redirect()->back()->with('error', lang('App.branchNotFound'));
            }
            
            $this->session->set('filter_branch_id', (int)$branchId);
            $this->session->setFlashdata('success', lang('App.filterByBranch') . ': ' . $branch['name']);
        }
        
        // Redirect back to the previous page
        return redirect()->back();
    }
}

