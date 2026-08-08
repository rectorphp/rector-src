<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    // force file cache to verify the persisted cache across runs, even in CI
    $rectorConfig->cacheClass(FileCacheStorage::class);
    $rectorConfig->parallel(0);

    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->sets([LevelSetList::UP_TO_PHP_82]);
};
