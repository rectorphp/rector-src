<?php

declare(strict_types=1);

use App\ConditionalEmptyConstructorRector;
use App\EnabledFlagCacheMetaExtension;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;

require_once __DIR__ . '/vendor/autoload.php';

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->cacheClass(FileCacheStorage::class);

    $rectorConfig->paths([
        __DIR__ . '/src/DeadConstructor.php',
    ]);

    $rectorConfig->rule(ConditionalEmptyConstructorRector::class);
    $rectorConfig->cacheMetaExtension(EnabledFlagCacheMetaExtension::class);
};
