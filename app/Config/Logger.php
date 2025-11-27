<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Log\Handlers\FileHandler;

class Logger extends BaseConfig
{
    /**
     * The threshold determines the minimum log level to display.
     */
    public $threshold = 4;

    /**
     * The date format to use in logs.
     */
    public string $dateFormat = 'Y-m-d H:i:s';

    /**
     * Handlers to use for logging.
     *
     * @var array<class-string, array<string, int|list<string>|string>>
     */
    public array $handlers = [
        FileHandler::class => [
            'handles'         => ['critical', 'alert', 'emergency', 'debug', 'error', 'info', 'notice', 'warning'],
            'fileExtension'   => '',
            'filePermissions' => 0644,
            'path'            => '',
        ],
    ];
}

