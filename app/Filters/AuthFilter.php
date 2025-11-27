<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Authentication Filter
 * 
 * Checks if user is logged in before allowing access to protected routes.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Check if user is authenticated
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (! $session->get('isLoggedIn')) {
            // Store intended URL for redirect after login
            $session->set('redirect_url', current_url());
            
            // Redirect to login page
            return redirect()->to('/login')->with('error', lang('App.pleaseLogin'));
        }
        
        // User is authenticated, continue
        return $request;
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

