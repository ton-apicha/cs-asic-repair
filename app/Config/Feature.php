<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Feature extends BaseConfig
{
    /**
     * Use improved new auto routing instead of the legacy
     */
    public bool $autoRoutesImproved = false;

    /**
     * Use filter execution order in 4.4 or before.
     */
    public bool $oldFilterOrder = false;

    /**
     * The behavior of `limit(0)` in Query Builder.
     */
    public bool $limitZeroAsAll = true;
}

