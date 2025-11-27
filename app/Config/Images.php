<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Images extends BaseConfig
{
    /**
     * Default handler
     */
    public string $defaultHandler = 'gd';

    /**
     * Library path
     */
    public string $libraryPath = '/usr/local/bin/convert';

    /**
     * Handlers configuration
     *
     * @var array<string, array<string, string>>
     */
    public array $handlers = [
        'gd'      => \CodeIgniter\Images\Handlers\GDHandler::class,
        'imagick' => \CodeIgniter\Images\Handlers\ImageMagickHandler::class,
    ];
}

