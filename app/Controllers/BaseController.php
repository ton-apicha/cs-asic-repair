<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation.
     *
     * @var list<string>
     */
    protected $helpers = ['form', 'url', 'text', 'date'];

    /**
     * Session instance
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Logged in user data
     *
     * @var array|null
     */
    protected $user;

    /**
     * Current branch ID
     *
     * @var int|null
     */
    protected $branchId;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = session();
        
        // Set locale from session or cookie
        $this->setLocale();
        
        // Load user data if logged in
        if ($this->session->get('isLoggedIn')) {
            $this->user = [
                'id'       => $this->session->get('userId'),
                'username' => $this->session->get('username'),
                'name'     => $this->session->get('name'),
                'role'     => $this->session->get('role'),
                'email'    => $this->session->get('email'),
            ];
            $this->branchId = $this->session->get('branchId');
        }
    }

    /**
     * Check if current user is admin (includes super admin)
     *
     * @return bool
     */
    protected function isAdmin(): bool
    {
        $role = $this->session->get('role');
        return in_array($role, ['admin', 'super_admin']);
    }

    /**
     * Check if current user is Super Admin
     * Super Admin = role 'super_admin' (can see all branches)
     *
     * @return bool
     */
    protected function isSuperAdmin(): bool
    {
        return $this->session->get('role') === 'super_admin';
    }

    /**
     * Check if current user is Branch Admin
     * Branch Admin = admin role with specific branch_id (can only see their branch)
     *
     * @return bool
     */
    protected function isBranchAdmin(): bool
    {
        return $this->session->get('role') === 'admin';
    }

    /**
     * Check if current user is technician
     *
     * @return bool
     */
    protected function isTechnician(): bool
    {
        return $this->session->get('role') === 'technician';
    }

    /**
     * Check if current user can access data from specific branch
     *
     * @param int|null $branchId The branch ID to check access for
     * @return bool
     */
    protected function canAccessBranch(?int $branchId): bool
    {
        // Super Admin can access all branches
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        // For branch-specific users, check if branch matches
        // Also allow access if data has no branch (branch_id = null)
        return $this->branchId === $branchId || $branchId === null;
    }

    /**
     * Get branch filter for database queries
     * Returns null for Super Admin (no filter = see all), branch_id for others
     *
     * @return int|null
     */
    protected function getBranchFilter(): ?int
    {
        // Super Admin: check if they have selected a specific branch to view
        if ($this->isSuperAdmin()) {
            $filterBranchId = $this->session->get('filter_branch_id');
            return $filterBranchId; // null = all, or specific branch
        }
        
        // Branch Admin / Technician: filter by their branch
        return $this->branchId;
    }

    /**
     * Get the branch ID to use when creating new records
     * Super Admin can choose, others use their assigned branch
     *
     * @param int|null $requestedBranchId Branch ID from form/request
     * @return int|null
     */
    protected function getCreateBranchId(?int $requestedBranchId = null): ?int
    {
        if ($this->isSuperAdmin()) {
            // Super Admin can specify branch or leave null (visible to all)
            return $requestedBranchId;
        }
        
        // Others always use their assigned branch
        return $this->branchId;
    }

    /**
     * Set the branch ID that Super Admin wants to view
     *
     * @param int|null $branchId Branch to view (null = all branches)
     */
    protected function setViewingBranch(?int $branchId): void
    {
        if ($this->isSuperAdmin()) {
            $this->session->set('filter_branch_id', $branchId);
        }
    }

    /**
     * Get current user ID
     *
     * @return int|null
     */
    protected function getUserId(): ?int
    {
        return $this->session->get('userId');
    }

    /**
     * Get current branch ID
     *
     * @return int|null
     */
    protected function getBranchId(): ?int
    {
        return $this->branchId;
    }

    /**
     * Return JSON response
     *
     * @param mixed $data
     * @param int   $status
     *
     * @return ResponseInterface
     */
    protected function jsonResponse($data, int $status = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON($data);
    }

    /**
     * Return success JSON response
     *
     * @param string $message
     * @param mixed  $data
     *
     * @return ResponseInterface
     */
    protected function successResponse(string $message = 'Success', $data = null): ResponseInterface
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return $this->jsonResponse($response);
    }

    /**
     * Return error JSON response
     *
     * @param string $message
     * @param int    $status
     * @param mixed  $errors
     *
     * @return ResponseInterface
     */
    protected function errorResponse(string $message = 'Error', int $status = 400, $errors = null): ResponseInterface
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        return $this->jsonResponse($response, $status);
    }

    /**
     * Get base view data with common variables
     *
     * @param array $data Additional data to merge
     *
     * @return array
     */
    protected function getViewData(array $data = []): array
    {
        $baseData = [
            'user'            => $this->user,
            'branchId'        => $this->branchId,
            'isAdmin'         => $this->isAdmin(),
            'isSuperAdmin'    => $this->isSuperAdmin(),
            'isBranchAdmin'   => $this->isBranchAdmin(),
            'filterBranchId'  => $this->session->get('filter_branch_id'),
            'locale'          => $this->request->getLocale(),
        ];
        
        // For Super Admin, load all branches for the branch selector
        if ($this->isSuperAdmin()) {
            $branchModel = model('BranchModel');
            $baseData['allBranches'] = $branchModel->where('is_active', 1)->findAll();
        }
        
        return array_merge($baseData, $data);
    }

    /**
     * Set locale from session or cookie
     */
    protected function setLocale(): void
    {
        $supportedLocales = ['en', 'th', 'zh'];
        $locale = null;

        // Check session first
        $locale = $this->session->get('locale');
        
        // Then check cookie
        if (!$locale) {
            $locale = $this->request->getCookie('locale');
        }
        
        // Validate locale
        if ($locale && in_array($locale, $supportedLocales)) {
            $this->request->setLocale($locale);
        }
    }

    /**
     * Execute database operation with error handling
     * Logs detailed errors but returns generic messages to users
     *
     * @param callable $operation Database operation to execute
     * @param string   $errorMessage Generic error message for user
     * @return mixed Operation result or false on error
     */
    protected function executeDbOperation(callable $operation, string $errorMessage = 'Operation failed')
    {
        try {
            return $operation();
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // Log detailed error (for debugging)
            log_message('error', '[DB Error] ' . $e->getMessage() . ' | File: ' . $e->getFile() . ':' . $e->getLine());
            
            // Store user-friendly error in session
            $this->session->setFlashdata('error', $errorMessage);
            
            return false;
        } catch (\Exception $e) {
            // Log unexpected errors
            log_message('error', '[Unexpected Error] ' . $e->getMessage() . ' | File: ' . $e->getFile() . ':' . $e->getLine());
            
            $this->session->setFlashdata('error', $errorMessage);
            
            return false;
        }
    }

    /**
     * Handle database error for JSON responses
     * Logs detailed errors but returns generic messages
     *
     * @param \Exception $e Exception object
     * @param string $genericMessage Generic error message for user
     * @return ResponseInterface
     */
    protected function handleDatabaseError(\Exception $e, string $genericMessage = 'Operation failed'): ResponseInterface
    {
        // Log detailed error
        log_message('error', '[DB Error] ' . $e->getMessage() . ' | File: ' . $e->getFile() . ':' . $e->getLine());
        
        // Return generic error to client (security: don't expose DB details)
        return $this->errorResponse($genericMessage, 500);
    }

    /**
     * Safe redirect with error message
     * Returns generic error messages to prevent information disclosure
     *
     * @param string $route Route to redirect to
     * @param string $errorMessage Error message to display
     * @param bool $withInput Whether to preserve input data
     * @return ResponseInterface
     */
    protected function redirectWithError(string $route, string $errorMessage, bool $withInput = false): ResponseInterface
    {
        $redirect = redirect()->to($route)->with('error', $errorMessage);
        
        if ($withInput) {
            $redirect = $redirect->withInput();
        }
        
        return $redirect;
    }
}

