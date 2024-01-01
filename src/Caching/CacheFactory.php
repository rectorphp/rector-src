<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Symfony\Component\Filesystem\Filesystem;

final readonly class CacheFactory
{
    public function __construct(
        private Filesystem $fileSystem
    ) {
    }

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
            if (! $this->fileSystem->exists($cacheDirectory)) {
                $this->fileSystem->mkdir($cacheDirectory);
            }

            $fileCacheStorage = new FileCacheStorage($cacheDirectory, $this->fileSystem);
            return new Cache($fileCacheStorage);
        }

        return new Cache(new MemoryCacheStorage());
    }
}
