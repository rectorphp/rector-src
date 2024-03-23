<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        __DIR__ . '/config1.php',
        __DIR__ . '/config2.php',
    ]);

    $rectorConfig->paths([
        __DIR__ . '/src/',
    ]);
};
