<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cookie extends BaseConfig
{
    /**
     * Cookie prefix
     */
    public string $prefix = '';

    /**
     * Cookie expiry time
     */
    public int $expires = 0;

    /**
     * Cookie path
     */
    public string $path = '/';

    /**
     * Cookie domain
     */
    public string $domain = '';

    /**
     * Secure cookie
     */
    public bool $secure = false;

    /**
     * HTTP only
     */
    public bool $httponly = true;

    /**
     * Same site
     */
    public string $samesite = 'Lax';

    /**
     * Raw cookie
     */
    public bool $raw = false;
}

