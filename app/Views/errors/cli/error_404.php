<?php

/**
 * CLI 404 Error Template
 */

echo "\n";
echo "=================================================\n";
echo "404 - NOT FOUND\n";
echo "=================================================\n";
echo "\n";
echo "Message: " . esc($message ?? 'The route you are looking for could not be found.') . "\n";
echo "\n";

