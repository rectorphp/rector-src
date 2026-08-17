<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
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

        // ensure cache directory exists
        if (! $this->fileSystem->exists($cacheDirectory)) {
            $this->fileSystem->mkdir($cacheDirectory);
        }

        $fileCacheStorage = new FileCacheStorage($cacheDirectory, $this->fileSystem);
        return new Cache($fileCacheStorage);
    }
}
