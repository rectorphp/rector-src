<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->cacheClass(FileCacheStorage::class);

    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->rule(RemoveEmptyClassMethodRector::class);
    $rectorConfig->rule(RemoveAlwaysTrueIfConditionRector::class);
};
