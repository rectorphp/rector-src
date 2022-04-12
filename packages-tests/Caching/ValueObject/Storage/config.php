<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Core\Configuration\Option;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::CACHE_DIR, sys_get_temp_dir() . '/_rector_cached_files_test');
    $parameters->set(Option::CACHE_CLASS, MemoryCacheStorage::class);
};
