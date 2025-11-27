<?php

namespace Config;

use CodeIgniter\Modules\Modules as BaseModules;

class Modules extends BaseModules
{
    /**
     * Should the application auto-discover the requested resources.
     *
     * @var bool
     */
    public $enabled = true;

    /**
     * If enabled, should discovery search Composer packages?
     *
     * @var array<string, bool>
     */
    public $composerPackages = [
        'only'    => [],
        'exclude' => [],
    ];

    /**
     * Aliases
     *
     * @var array<string, string>
     */
    public $aliases = [];
}
