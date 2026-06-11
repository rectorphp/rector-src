<?php

declare(strict_types=1);

namespace Rector\Caching\Detector;

use Rector\Caching\Cache;
use Rector\Caching\Config\FileHashComputer;
use Rector\Caching\Enum\CacheKey;
use Rector\Caching\FileDependencyCollector;
use Rector\Util\FileHasher;

/**
 * Inspired by https://github.com/symplify/symplify/pull/90/files#diff-72041b2e1029a08930e13d79d298ef11
 *
 * @see \Rector\Tests\Caching\Detector\ChangedFilesDetectorTest
 */
final class ChangedFilesDetector
{
    /**
     * @var array<string, true>
     */
    private array $cacheableFiles = [];

    public function __construct(
        private readonly FileHashComputer $fileHashComputer,
        private readonly Cache $cache,
        private readonly FileHasher $fileHasher,
        private readonly FileDependencyCollector $fileDependencyCollector
    ) {
    }

    public function cacheFile(string $filePath): void
    {
        $filePathCacheKey = $this->getFilePathCacheKey($filePath);

        if (! isset($this->cacheableFiles[$filePathCacheKey])) {
            return;
        }

        // a failed capture means a possibly incomplete set, skip caching so the file is reprocessed
        $dependencyHashes = $this->fileDependencyCollector->getDependencyFileHashes($filePath);
        if ($dependencyHashes === null) {
            return;
        }

        // the file may have just been written, recompute its hash fresh
        // rather than trusting a memo entry from the pre-write filter pass
        $this->fileDependencyCollector->forgetContentHash($filePath);
        $ownHash = $this->fileDependencyCollector->contentHash($filePath);
        if ($ownHash === null) {
            return;
        }

        // store the own content hash plus one per dependency, so a dependency change
        // invalidates this file even when its own content is unchanged
        $this->cache->save($filePathCacheKey, CacheKey::FILE_HASH_KEY, [
            'hash' => $ownHash,
            'deps' => $dependencyHashes,
        ]);
    }

    public function addCacheableFile(string $filePath): void
    {
        $filePathCacheKey = $this->getFilePathCacheKey($filePath);
        $this->cacheableFiles[$filePathCacheKey] = true;
    }

    public function hasFileChanged(string $filePath): bool
    {
        $fileInfoCacheKey = $this->getFilePathCacheKey($filePath);
        $cachedValue = $this->cache->load($fileInfoCacheKey, CacheKey::FILE_HASH_KEY);

        // no value to compare against → be defensive and assume changed
        if ($cachedValue === null) {
            return true;
        }

        // legacy string entry → own-hash comparison only, rewritten in the new format on next cacheFile()
        if (is_string($cachedValue)) {
            return $this->fileDependencyCollector->contentHash($filePath) !== $cachedValue;
        }

        if (! is_array($cachedValue)) {
            return true;
        }

        // own content changed
        if (($cachedValue['hash'] ?? null) !== $this->fileDependencyCollector->contentHash($filePath)) {
            return true;
        }

        // any recorded dependency changed
        return $this->fileDependencyCollector->hasAnyChangedDependency($cachedValue['deps'] ?? []);
    }

    public function invalidateFile(string $filePath): void
    {
        $fileInfoCacheKey = $this->getFilePathCacheKey($filePath);
        $this->cache->clean($fileInfoCacheKey);
        unset($this->cacheableFiles[$fileInfoCacheKey]);
    }

    public function clear(): void
    {
        $this->cache->clear();
    }

    /**
     * @api
     */
    public function setFirstResolvedConfigFileInfo(string $filePath): void
    {
        // the first config is core to all → if it was changed, just invalidate it
        $configHash = $this->fileHashComputer->compute($filePath);
        $this->storeConfigurationDataHash($filePath, $configHash);
    }

    private function resolvePath(string $filePath): string
    {
        $realPath = realpath($filePath);
        if ($realPath === false) {
            return $filePath;
        }

        return $realPath;
    }

    private function getFilePathCacheKey(string $filePath): string
    {
        return $this->fileHasher->hash($this->resolvePath($filePath));
    }

    private function storeConfigurationDataHash(string $filePath, string $configurationHash): void
    {
        $key = CacheKey::CONFIGURATION_HASH_KEY . '_' . $this->getFilePathCacheKey($filePath);
        $this->invalidateCacheIfConfigurationChanged($key, $configurationHash);

        $this->cache->save($key, CacheKey::CONFIGURATION_HASH_KEY, $configurationHash);
    }

    private function invalidateCacheIfConfigurationChanged(string $key, string $configurationHash): void
    {
        $oldCachedValue = $this->cache->load($key, CacheKey::CONFIGURATION_HASH_KEY);
        if ($oldCachedValue === null) {
            return;
        }

        if ($oldCachedValue === $configurationHash) {
            return;
        }

        // should be unique per getcwd()
        $this->clear();
    }
}
