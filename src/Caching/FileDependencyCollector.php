<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Contract\DependencyInjection\ResettableInterface;
use Rector\Util\FileHasher;

/**
 * Collects the files each processed file depends on, as surfaced by PHPStan's
 * DependencyResolver during scope resolution, and provides memoized content hashes
 * for dependency validation by the unchanged-files cache.
 */
final class FileDependencyCollector implements ResettableInterface
{
    /**
     * @var array<string, array<string, true>>
     */
    private array $dependenciesByFile = [];

    /**
     * files whose capture threw, their possibly partial set must never be cached
     *
     * @var array<string, true>
     */
    private array $failedFiles = [];

    /**
     * keyed by the given path, so memo hits skip realpath() as well
     *
     * @var array<string, string|null>
     */
    private array $contentHashMemo = [];

    /**
     * a function's signature dependencies are identical at every call site
     *
     * @var array<string, string[]>
     */
    private array $functionDependencyFilesMemo = [];

    public function __construct(
        private readonly FileHasher $fileHasher,
    ) {
    }

    public function record(string $filePath, string $dependencyFilePath): void
    {
        if ($filePath === $dependencyFilePath) {
            return;
        }

        $this->dependenciesByFile[$filePath][$dependencyFilePath] = true;
    }

    public function markFailed(string $filePath): void
    {
        $this->failedFiles[$filePath] = true;
    }

    /**
     * @return string[]|null
     */
    public function getMemoizedFunctionDependencyFiles(string $functionKey): ?array
    {
        return $this->functionDependencyFilesMemo[$functionKey] ?? null;
    }

    /**
     * @param string[] $dependencyFiles
     */
    public function memoizeFunctionDependencyFiles(string $functionKey, array $dependencyFiles): void
    {
        $this->functionDependencyFilesMemo[$functionKey] = $dependencyFiles;
    }

    /**
     * @return array<string, string>|null null when capture failed and the set cannot be trusted
     */
    public function getDependencyFileHashes(string $filePath): ?array
    {
        if (isset($this->failedFiles[$filePath])) {
            return null;
        }

        $dependencyHashes = [];
        foreach (array_keys($this->dependenciesByFile[$filePath] ?? []) as $dependencyFile) {
            $dependencyHash = $this->contentHash($dependencyFile);
            if ($dependencyHash !== null) {
                $dependencyHashes[$dependencyFile] = $dependencyHash;
            }
        }

        return $dependencyHashes;
    }

    /**
     * @param array<string, string> $recordedDependencyHashes
     */
    public function hasAnyChangedDependency(array $recordedDependencyHashes): bool
    {
        foreach ($recordedDependencyHashes as $dependencyFile => $recordedHash) {
            if ($this->contentHash($dependencyFile) !== $recordedHash) {
                return true;
            }
        }

        return false;
    }

    /**
     * null when the file does not exist, e.g. a deleted dependency, which callers treat as changed
     */
    public function contentHash(string $filePath): ?string
    {
        if (array_key_exists($filePath, $this->contentHashMemo)) {
            return $this->contentHashMemo[$filePath];
        }

        $resolvedPath = $this->resolvePath($filePath);
        if (! is_file($resolvedPath)) {
            return $this->contentHashMemo[$filePath] = null;
        }

        return $this->contentHashMemo[$filePath] = $this->fileHasher->hashFiles([$resolvedPath]);
    }

    /**
     * drop a memoized hash, e.g. after the file has been written mid-run
     */
    public function forgetContentHash(string $filePath): void
    {
        unset($this->contentHashMemo[$filePath]);
    }

    public function reset(): void
    {
        $this->dependenciesByFile = [];
        $this->failedFiles = [];
        $this->contentHashMemo = [];
        $this->functionDependencyFilesMemo = [];
    }

    private function resolvePath(string $filePath): string
    {
        $realPath = realpath($filePath);
        if ($realPath === false) {
            return $filePath;
        }

        return $realPath;
    }
}
