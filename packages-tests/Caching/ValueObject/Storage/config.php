<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->cacheDirectory(sys_get_temp_dir() . '/_rector_cached_files_test');
    $rectorConfig->cacheClass(MemoryCacheStorage::class);
};
