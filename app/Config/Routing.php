<?php

namespace Config;

use CodeIgniter\Config\Routing as BaseRouting;

class Routing extends BaseRouting
{
    /**
     * Default namespace for Controllers.
     */
    public string $defaultNamespace = 'App\Controllers';

    /**
     * Default controller
     */
    public string $defaultController = 'DashboardController';

    /**
     * Default method
     */
    public string $defaultMethod = 'index';

    /**
     * Translate URI dashes
     */
    public bool $translateURIDashes = false;

    /**
     * Auto route
     */
    public bool $autoRoute = false;

    /**
     * Route files
     *
     * @var list<string>
     */
    public array $routeFiles = [
        APPPATH . 'Config/Routes.php',
    ];

    /**
     * Priority routes
     */
    public bool $prioritize = false;

    /**
     * Module routes
     *
     * @var array<string, array>
     */
    public array $moduleRoutes = [];
}

