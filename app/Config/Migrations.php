<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Migrations extends BaseConfig
{
    /**
     * Whether migrations are enabled or disabled.
     */
    public bool $enabled = true;

    /**
     * Name of the table that stores migration state.
     */
    public string $table = 'migrations';

    /**
     * The format of timestamp to use.
     */
    public string $timestampFormat = 'Y-m-d-His_';
}

