<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel(maxNumberOfProcess: 2, jobSize: 1);

    // this to make reliable result with local (that default to use FileCacheStorage) vs CI (that default to use MemoryCacheStorage)
    $rectorConfig->cacheClass(MemoryCacheStorage::class);

    $rectorConfig->paths([
        __DIR__ . '/src/',
    ]);

    $rectorConfig->rules([
        RemoveUnusedPrivatePropertyRector::class,
        RemoveUnusedPrivateMethodRector::class,
    ]);
};
