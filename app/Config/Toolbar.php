<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Debug\Toolbar\Collectors\Database;
use CodeIgniter\Debug\Toolbar\Collectors\Events;
use CodeIgniter\Debug\Toolbar\Collectors\Files;
use CodeIgniter\Debug\Toolbar\Collectors\Logs;
use CodeIgniter\Debug\Toolbar\Collectors\Routes;
use CodeIgniter\Debug\Toolbar\Collectors\Timers;
use CodeIgniter\Debug\Toolbar\Collectors\Views;

class Toolbar extends BaseConfig
{
    /**
     * Collectors to use
     *
     * @var list<class-string>
     */
    public array $collectors = [
        Timers::class,
        Database::class,
        Logs::class,
        Views::class,
        Files::class,
        Routes::class,
        Events::class,
    ];

    /**
     * Collect var data
     * 
     * If set to false var data from the views will not be collected. Useful to
     * avoid high memory usage when there are lots of data passed to the view.
     */
    public bool $collectVarData = true;

    /**
     * Max history
     */
    public int $maxHistory = 20;

    /**
     * Views path
     */
    public string $viewsPath = SYSTEMPATH . 'Debug/Toolbar/Views/';

    /**
     * Max queries
     */
    public int $maxQueries = 100;

    /**
     * Watched directories
     *
     * @var list<string>
     */
    public array $watchedDirectories = [
        'app',
    ];

    /**
     * Watched extensions
     *
     * @var list<string>
     */
    public array $watchedExtensions = [
        'php',
        'css',
        'js',
        'html',
    ];
}

