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
     * Check if current user is admin
     *
     * @return bool
     */
    protected function isAdmin(): bool
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
            'user'     => $this->user,
            'branchId' => $this->branchId,
            'isAdmin'  => $this->isAdmin(),
            'locale'   => $this->request->getLocale(),
        ];
        
        return array_merge($baseData, $data);
    }
}

