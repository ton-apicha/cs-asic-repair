<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Kint extends BaseConfig
{
    /**
     * Plugins configuration
     *
     * @var list<string>|null
     */
    public ?array $plugins = null;

    /**
     * Max depth
     */
    public int $maxDepth = 6;

    /**
     * Display called from
     */
    public bool $displayCalledFrom = true;

    /**
     * Expanded
     */
    public bool $expanded = false;

    /**
     * Rich theme (aante-light, original, solarized, solarized-dark)
     */
    public string $richTheme = 'original';

    /**
     * Rich folder
     */
    public bool $richFolder = false;

    /**
     * Rich object plugins
     *
     * @var list<string>|null
     */
    public ?array $richObjectPlugins = null;

    /**
     * Rich tab plugins
     *
     * @var list<string>|null
     */
    public ?array $richTabPlugins = null;

    /**
     * CLI colors
     */
    public bool $cliColors = true;

    /**
     * CLI force UTF8
     */
    public bool $cliForceUTF8 = false;

    /**
     * CLI detect width
     */
    public bool $cliDetectWidth = true;

    /**
     * CLI min width
     */
    public int $cliMinWidth = 40;
}
