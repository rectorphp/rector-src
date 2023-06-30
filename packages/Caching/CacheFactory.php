<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\ParameterProvider;
use Symfony\Component\Filesystem\Filesystem;

final class CacheFactory
{
    public function __construct(
        private readonly Filesystem $fileSystem
    ) {
    }

    /**
     * @api config factory
     */
    public function create(): Cache
    {
        $cacheDirectory = ParameterProvider::provideStringParameter(Option::CACHE_DIR);

        $cacheClass = FileCacheStorage::class;
        if (ParameterProvider::hasParameter(Option::CACHE_CLASS)) {
            $cacheClass = ParameterProvider::provideStringParameter(Option::CACHE_CLASS);
        }

        if ($cacheClass === FileCacheStorage::class) {
            // ensure cache directory exists
            if (! $this->fileSystem->exists($cacheDirectory)) {
                $this->fileSystem->mkdir($cacheDirectory);
            }

            $fileCacheStorage = new FileCacheStorage($cacheDirectory, $this->fileSystem);
            return new Cache($fileCacheStorage);
        }

        return new Cache(new MemoryCacheStorage());
    }
}
