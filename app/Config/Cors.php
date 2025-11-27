<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    /**
     * The default CORS configuration.
     *
     * @var array<string, array<string, list<string>|bool|int>>
     */
    public array $default = [
        'allowedOrigins'         => [],
        'allowedOriginsPatterns' => [],
        'supportsCredentials'    => false,
        'allowedHeaders'         => [],
        'exposedHeaders'         => [],
        'allowedMethods'         => [],
        'maxAge'                 => 7200,
    ];
}

