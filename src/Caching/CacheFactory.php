<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;

final readonly class CacheFactory
{
    /**
     * @api config factory
     */
    public function create(): Cache
    {
        $cacheDirectory = SimpleParameterProvider::provideStringParameter(Option::CACHE_DIR);

        $cacheClass = FileCacheStorage::class;

        if (SimpleParameterProvider::hasParameter(Option::CACHE_CLASS)) {
            $cacheClass = SimpleParameterProvider::provideStringParameter(Option::CACHE_CLASS);
        }

        if ($cacheClass === FileCacheStorage::class) {
            // ensure cache directory exists
            if (! is_dir($cacheDirectory)) {
                mkdir($cacheDirectory);
            }

            $fileCacheStorage = new FileCacheStorage($cacheDirectory);
            return new Cache($fileCacheStorage);
        }

        return new Cache(new MemoryCacheStorage());
    }
}
