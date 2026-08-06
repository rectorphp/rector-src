<?php

declare(strict_types=1);

namespace Rector\Caching;

use OndraM\CiDetector\CiDetector;
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
        // in CI the workspace is ephemeral and usually starts from scratch,
        // so a file cache that is never read again is only wasted IO → use faster in-memory cache
        if (new CiDetector()->isCiDetected()) {
            return new Cache(new MemoryCacheStorage());
        }

        $cacheDirectory = SimpleParameterProvider::provideStringParameter(Option::CACHE_DIR);

        // ensure cache directory exists
        if (! $this->fileSystem->exists($cacheDirectory)) {
            $this->fileSystem->mkdir($cacheDirectory);
        }

        $fileCacheStorage = new FileCacheStorage($cacheDirectory, $this->fileSystem);
        return new Cache($fileCacheStorage);
    }
}
