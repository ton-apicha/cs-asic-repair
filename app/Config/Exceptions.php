<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Debug\ExceptionHandler;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use Psr\Log\LogLevel;
use Throwable;

class Exceptions extends BaseConfig
{
    /**
     * If true, Kint will be used to display any thrown exceptions.
     */
    public bool $log = true;

    /**
     * The type of exceptions that should be ignored.
     *
     * @var list<class-string<Throwable>>
     */
    public array $ignoreCodes = [404];

    /**
     * Path to the directory where error view files are stored.
     */
    public string $errorViewPath = APPPATH . 'Views/errors';

    /**
     * If true, logs deprecation warnings.
     */
    public bool $logDeprecations = true;

    /**
     * Log level for deprecations.
     */
    public string $deprecationLogLevel = LogLevel::WARNING;

    /**
     * Error Logging Threshold
     *
     * @var array<int, string>
     */
    public array $logLevel = [
        E_ERROR   => LogLevel::CRITICAL,
        E_WARNING => LogLevel::WARNING,
        E_PARSE   => LogLevel::ERROR,
        E_NOTICE  => LogLevel::WARNING,
    ];

    /**
     * Sensitive data keys that should be hidden from the trace.
     *
     * @var list<string>
     */
    public array $sensitiveDataInTrace = [];

    /**
     * Returns the handler for exceptions.
     */
    public function handler(int $statusCode, Throwable $exception): ExceptionHandlerInterface
    {
        return new ExceptionHandler($this);
    }
}
