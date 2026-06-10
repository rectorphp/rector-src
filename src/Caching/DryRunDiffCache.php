<?php

declare(strict_types=1);

namespace Rector\Caching;

use Rector\Caching\Enum\CacheKey;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Parallel\ValueObject\BridgeItem;
use Rector\Util\FileHasher;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\FileProcessResult;
use Rector\ValueObject\Reporting\FileDiff;

/**
 * Caches dry-run FileDiffs. Files with a pending diff are never marked clean, as a
 * dry-run must keep reporting them, so they are fully reprocessed on every run. When
 * the file and all its captured dependencies are unchanged, the cached diff is
 * replayed instead, skipping the whole pipeline including PHPStan scope resolution.
 *
 * @see \Rector\Tests\Caching\DryRunDiffCacheTest
 */
final readonly class DryRunDiffCache
{
    public function __construct(
        private Cache $cache,
        private FileHasher $fileHasher,
        private FileDependencyCollector $fileDependencyCollector,
    ) {
    }

    public function load(File $file, Configuration $configuration): ?FileProcessResult
    {
        $cached = $this->cache->load($this->key($file), CacheKey::FILE_DIFF_KEY);
        if (! is_array($cached)) {
            return null;
        }

        // own content + config must match
        if (($cached['hash'] ?? null) !== $this->contentHash($file, $configuration)) {
            return null;
        }

        // every dependency captured last run must still hash to the same value
        $cachedDependencyHashes = $cached['deps'] ?? null;
        if (! is_array($cachedDependencyHashes)) {
            return null;
        }

        if ($this->fileDependencyCollector->hasAnyChangedDependency($cachedDependencyHashes)) {
            return null;
        }

        $diffJson = $cached['diff'] ?? null;
        if (! is_array($diffJson)) {
            return null;
        }

        // a rule can report line changes while printing identical content, so a diff
        // does not imply a changed file → replay the original flag or warm runs
        // would report phantom changed files
        $hasChanged = $cached['changed'] ?? null;
        if (! is_bool($hasChanged)) {
            return null;
        }

        return new FileProcessResult([], FileDiff::decode($diffJson), $hasChanged);
    }

    public function save(File $file, Configuration $configuration, FileDiff $fileDiff, bool $hasChanged): void
    {
        // a failed capture means a possibly incomplete set, skip caching so the file is reprocessed
        $dependencyHashes = $this->fileDependencyCollector->getDependencyFileHashes($file->getFilePath());
        if ($dependencyHashes === null) {
            return;
        }

        $diffJson = $fileDiff->jsonSerialize();
        // decompose objects to plain arrays so the var_export-based file cache can round-trip them
        $diffJson[BridgeItem::RECTORS_WITH_LINE_CHANGES] = array_map(
            static fn (RectorWithLineChange $rectorWithLineChange): array => $rectorWithLineChange->jsonSerialize(),
            $diffJson[BridgeItem::RECTORS_WITH_LINE_CHANGES]
        );

        $this->cache->save($this->key($file), CacheKey::FILE_DIFF_KEY, [
            'hash' => $this->contentHash($file, $configuration),
            'deps' => $dependencyHashes,
            'diff' => $diffJson,
            'changed' => $hasChanged,
        ]);
    }

    private function key(File $file): string
    {
        return 'diff_' . $this->fileHasher->hash($file->getFilePath());
    }

    private function contentHash(File $file, Configuration $configuration): string
    {
        // --no-diffs changes the produced FileDiff content but is not part of the
        // parameter hash, include it so entries do not cross-replay
        return $this->fileHasher->hash($file->getOriginalFileContent())
            . '_' . SimpleParameterProvider::hash()
            . ($configuration->shouldShowDiffs() ? '' : '_no-diffs');
    }
}
