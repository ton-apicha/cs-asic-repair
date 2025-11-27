<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\FileHandler;

class Session extends BaseConfig
{
    /**
     * The session driver to use.
     */
    public string $driver = FileHandler::class;

    /**
     * The session cookie name.
     */
    public string $cookieName = 'ci_session';

    /**
     * Number of seconds before expiry (0 = browser close).
     */
    public int $expiration = 7200;

    /**
     * Path to save session files.
     */
    public string $savePath = WRITEPATH . 'session';

    /**
     * Should session ID be regenerated?
     */
    public bool $matchIP = false;

    /**
     * Time to regenerate session ID.
     */
    public int $timeToUpdate = 300;

    /**
     * Destroy old session after regeneration.
     */
    public bool $regenerateDestroy = false;

    /**
     * Database group for DB sessions.
     */
    public ?string $DBGroup = null;
}

