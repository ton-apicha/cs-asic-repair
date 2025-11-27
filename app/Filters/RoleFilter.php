<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Role-based Access Control Filter
 * 
 * Checks if user has the required role before allowing access.
 */
class RoleFilter implements FilterInterface
{
    /**
     * Check if user has the required role
     *
     * @param RequestInterface $request
     * @param array|null       $arguments Role names to check (e.g., ['admin'])
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // First check if user is logged in
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', lang('App.pleaseLogin'));
        }
        
        // Get user's role from session
        $userRole = $session->get('role');
        
        // If no arguments specified, allow access
        if (empty($arguments)) {
            return $request;
        }
        
        // Check if user's role is in the allowed roles
        if (in_array($userRole, $arguments)) {
            return $request;
        }
        
        // User doesn't have the required role
        return redirect()->to('/dashboard')->with('error', lang('App.accessDenied'));
    }

    /**
     * After filter - not used
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

