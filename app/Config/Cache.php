<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cache extends BaseConfig
{
    /**
     * The handler that should be used as the primary cache.
     */
    public string $handler = 'file';

    /**
     * The backup handler to use when the primary handler fails.
     */
    public string $backupHandler = 'dummy';

    /**
     * The path for file cache.
     */
    public string $storePath = WRITEPATH . 'cache/';

    /**
     * Prefix all cache key names with this string.
     */
    public string $prefix = '';

    /**
     * Time to live in seconds for cached items.
     */
    public int $ttl = 60;

    /**
     * Reserved characters that cannot be used in Key or Tag names
     */
    public string $reservedCharacters = '{}()/\@:';

    /**
     * Whether to take the URL query string into account when generating cache key.
     */
    public bool $cacheQueryString = false;

    /**
     * File handler options
     *
     * @var array<string, int|string|null>
     */
    public array $file = [
        'storePath' => WRITEPATH . 'cache/',
        'mode'      => 0640,
    ];

    /**
     * Memcached handler options
     *
     * @var array<string, bool|int|string|list<array<string, int|string|null>>>
     */
    public array $memcached = [
        'host'   => '127.0.0.1',
        'port'   => 11211,
        'weight' => 1,
        'raw'    => false,
    ];

    /**
     * Redis handler options
     *
     * @var array<string, int|string|null>
     */
    public array $redis = [
        'host'     => '127.0.0.1',
        'password' => null,
        'port'     => 6379,
        'timeout'  => 0,
        'database' => 0,
    ];

    /**
     * Valid cache handlers
     *
     * @var array<string, string>
     */
    public array $validHandlers = [
        'dummy'     => \CodeIgniter\Cache\Handlers\DummyHandler::class,
        'file'      => \CodeIgniter\Cache\Handlers\FileHandler::class,
        'memcached' => \CodeIgniter\Cache\Handlers\MemcachedHandler::class,
        'predis'    => \CodeIgniter\Cache\Handlers\PredisHandler::class,
        'redis'     => \CodeIgniter\Cache\Handlers\RedisHandler::class,
        'wincache'  => \CodeIgniter\Cache\Handlers\WincacheHandler::class,
    ];
}
