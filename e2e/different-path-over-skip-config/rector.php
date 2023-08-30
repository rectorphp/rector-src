<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->skip([
        RemoveEmptyClassMethodRector::class => [
            __DIR__ . '/src/controllers',
        ],
    ]);

    $rectorConfig->rule(RemoveEmptyClassMethodRector::class);
};
