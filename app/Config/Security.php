<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    /**
     * CSRF Protection Method
     */
    public string $csrfProtection = 'session';

    /**
     * Randomize token
     */
    public bool $tokenRandomize = true;

    /**
     * Token name
     */
    public string $tokenName = 'csrf_token';

    /**
     * Header name
     */
    public string $headerName = 'X-CSRF-TOKEN';

    /**
     * Cookie name
     */
    public string $cookieName = 'csrf_cookie_name';

    /**
     * Token expires (seconds)
     */
    public int $expires = 7200;

    /**
     * Regenerate token
     */
    public bool $regenerate = true;

    /**
     * Redirect on failure
     */
    public bool $redirect = false;

    /**
     * Same site cookie setting
     */
    public string $samesite = 'Lax';
}

