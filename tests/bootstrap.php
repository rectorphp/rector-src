<?php

declare(strict_types=1);

// make local php-parser a priority to avoid conflict
require_once __DIR__ . '/../preload.php';
require_once __DIR__ . '/../vendor/autoload.php';

// silent deprecations, since we test them
// error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(E_ALL);
set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

// performance boost
gc_disable();
