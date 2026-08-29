<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

// all PHP version rules, oldest to newest; each rule gates itself at runtime
// via MinPhpVersionInterface, so only rules up to the target PHP version apply
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        __DIR__ . '/php52.php',
        __DIR__ . '/php53.php',
        __DIR__ . '/php54.php',
        __DIR__ . '/php55.php',
        __DIR__ . '/php56.php',
        __DIR__ . '/php70.php',
        __DIR__ . '/php71.php',
        __DIR__ . '/php72.php',
        __DIR__ . '/php73.php',
        __DIR__ . '/php74.php',
        __DIR__ . '/php80.php',
        __DIR__ . '/php81.php',
        __DIR__ . '/php82.php',
        __DIR__ . '/php83.php',
        __DIR__ . '/php84.php',
        __DIR__ . '/php85.php',
        __DIR__ . '/php86.php',
    ]);
};
