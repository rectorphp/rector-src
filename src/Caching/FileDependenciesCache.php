<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\Enum\CacheKey;
use Rector\Util\FileHasher;

final readonly class FileDependenciesCache
{
    public function __construct(
        private Cache $cache,
        private FileHasher $fileHasher
    ) {
    }

    /**
     * @param string[] $dependencies
     */
    public function cacheFileDependencies(string $filePath, array $dependencies): void
    {
        $filePathCacheKey = $this->getFilePathCacheKey($filePath);

        $this->cache->save($filePathCacheKey, CacheKey::FILE_DEPENDENCIES_KEY, json_encode($dependencies));
    }

    /**
     * @return string[]|null
     */
    public function getFileDependencies(string $filePath): ?array
    {
        $fileInfoCacheKey = $this->getFilePathCacheKey($filePath);
        $cachedValue = $this->cache->load($fileInfoCacheKey, CacheKey::FILE_DEPENDENCIES_KEY);

        return is_string($cachedValue) ? json_decode($cachedValue) : null;
    }

    /**
     * @param string[] $allFiles
     */
    public function cacheAllFiles(array $allFiles): void
    {
        $cacheKey = $this->fileHasher->hash(CacheKey::ALL_FILES_KEY);

        $this->cache->save($cacheKey, CacheKey::ALL_FILES_KEY, json_encode(array_values($allFiles)));
    }

    /**
     * @return string[]
     */
    public function getAllFiles(): array
    {
        $cacheKey = $this->fileHasher->hash(CacheKey::ALL_FILES_KEY);
        $cachedValue = $this->cache->load($cacheKey, CacheKey::ALL_FILES_KEY);

        return is_string($cachedValue) ? json_decode($cachedValue) : [];
    }

    private function getFilePathCacheKey(string $filePath): string
    {
        return $this->fileHasher->hash($this->fileHasher->resolvePath($filePath) . 'file_dependencies');
    }
}
