<?php

/**
 * CLI Error Template
 */

// Ensure $exception is defined
$exception = $exception ?? null;

echo "\n";
echo "=================================================\n";
echo "EXCEPTION: " . ($title ?? 'Error') . "\n";
echo "=================================================\n";

if ($exception !== null) {
    echo "\nMessage: " . $exception->getMessage() . "\n";
    echo "File: " . $exception->getFile() . "\n";
    echo "Line: " . $exception->getLine() . "\n";
    
    if (ENVIRONMENT !== 'production') {
        echo "\nTrace:\n";
        echo $exception->getTraceAsString() . "\n";
    }
}

echo "\n";

