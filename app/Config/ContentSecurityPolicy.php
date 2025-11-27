<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ContentSecurityPolicy extends BaseConfig
{
    /**
     * Report Only flag
     */
    public bool $reportOnly = false;

    /**
     * Report URI
     */
    public ?string $reportURI = null;

    /**
     * Upgrade Insecure Requests
     */
    public bool $upgradeInsecureRequests = false;

    // Sources

    /**
     * Default source
     *
     * @var string|list<string>
     */
    public $defaultSrc = 'none';

    /**
     * Script source
     *
     * @var string|list<string>
     */
    public $scriptSrc = 'self';

    /**
     * Style source
     *
     * @var string|list<string>
     */
    public $styleSrc = 'self';

    /**
     * Image source
     *
     * @var string|list<string>
     */
    public $imageSrc = 'self';

    /**
     * Base URI
     *
     * @var string|list<string>|null
     */
    public $baseURI = null;

    /**
     * Child source
     *
     * @var string|list<string>|null
     */
    public $childSrc = null;

    /**
     * Connect source
     *
     * @var string|list<string>
     */
    public $connectSrc = 'self';

    /**
     * Font source
     *
     * @var string|list<string>|null
     */
    public $fontSrc = null;

    /**
     * Form action
     *
     * @var string|list<string>|null
     */
    public $formAction = null;

    /**
     * Frame ancestors
     *
     * @var string|list<string>|null
     */
    public $frameAncestors = null;

    /**
     * Frame source
     *
     * @var string|list<string>|null
     */
    public $frameSrc = null;

    /**
     * Media source
     *
     * @var string|list<string>|null
     */
    public $mediaSrc = null;

    /**
     * Object source
     *
     * @var string|list<string>|null
     */
    public $objectSrc = null;

    /**
     * Plugin types
     *
     * @var string|list<string>|null
     */
    public $pluginTypes = null;

    /**
     * Sandbox
     *
     * @var string|list<string>|null
     */
    public $sandbox = null;

    /**
     * Nonce tag
     */
    public string $styleNonceTag = '{csp-style-nonce}';

    /**
     * Script nonce tag
     */
    public string $scriptNonceTag = '{csp-script-nonce}';

    /**
     * Auto nonce
     */
    public bool $autoNonce = true;
}

