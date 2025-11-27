<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Encryption extends BaseConfig
{
    /**
     * Encryption key starter
     */
    public string $key = '';

    /**
     * Encryption driver to use
     */
    public string $driver = 'OpenSSL';

    /**
     * Block size for padding
     */
    public int $blockSize = 16;

    /**
     * SodiumHandler digest
     */
    public string $digest = 'SHA512';
}

