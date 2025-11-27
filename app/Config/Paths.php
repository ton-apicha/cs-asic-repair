<?php

namespace Config;

/**
 * Paths
 *
 * Holds the paths that are used by the system to
 * locate the main directories.
 */
class Paths
{
    /**
     * The path to the application directory.
     */
    public string $appDirectory = __DIR__ . '/..';

    /**
     * The path to the public directory.
     */
    public string $publicDirectory = __DIR__ . '/../../public';

    /**
     * The path to the writable directory.
     */
    public string $writableDirectory = __DIR__ . '/../../writable';

    /**
     * The path to the tests directory.
     */
    public string $testsDirectory = __DIR__ . '/../../tests';

    /**
     * The path to the framework system directory.
     * Must have a trailing slash.
     */
    public string $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    /**
     * The path to the views directory.
     */
    public string $viewDirectory = __DIR__ . '/../Views';
}

