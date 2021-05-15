<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

use Nette\Caching\Cache;
use Nette\Utils\Strings;
use Rector\Core\Configuration\Configuration;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symplify\Skipper\SkipCriteriaResolver\SkippedPathsResolver;
use Symplify\SmartFileSystem\FileSystemFilter;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Rector\Core\Tests\FileSystem\FilesFinder\FilesFinderTest
 */
final class FilesFinder
{
    /**
     * @var string
     * @see https://regex101.com/r/e1jm7v/1
     */
    private const STARTS_WITH_ASTERISK_REGEX = '#^\*(.*?)[^*]$#';

    /**
     * @var string
     * @see https://regex101.com/r/EgJQyZ/1
     */
    private const ENDS_WITH_ASTERISK_REGEX = '#^[^*](.*?)\*$#';

    public function __construct(
        private FilesystemTweaker $filesystemTweaker,
        private FinderSanitizer $finderSanitizer,
        private FileSystemFilter $fileSystemFilter,
        private SkippedPathsResolver $skippedPathsResolver,
        private Configuration $configuration,
        private Cache $cache
    ) {
    }

    /**
     * @param string[] $source
     * @param string[] $suffixes
     * @return SmartFileInfo[]
     */
    public function findInDirectoriesAndFiles(array $source, array $suffixes): array
    {
        if (! $this->configuration->isCacheEnabled() || $this->configuration->shouldClearCache()) {
            $this->cache->clean([
                Cache::ALL => true,
            ]);

            return $this->collectFileInfos($source, $suffixes);
        }

        $cacheKey = md5(serialize($source) . serialize($suffixes));
        $loadCache = $this->cache->load($cacheKey);

        if ($loadCache) {
            return $this->getSmartFileInfosFromStringFiles($loadCache);
        }

        $filesAndDirectories = $this->filesystemTweaker->resolveWithFnmatch($source);

        $files = $this->fileSystemFilter->filterFiles($filesAndDirectories);
        $directories = $this->fileSystemFilter->filterDirectories($filesAndDirectories);

        /**
         * @var string[] $filesInDirectories
         */
        $filesInDirectories = $this->findInDirectories($directories, $suffixes, false);
        $files = array_merge($files, $filesInDirectories);
        $this->cache->save($cacheKey, $files);

        return $this->getSmartFileInfosFromStringFiles($files);
    }

    /**
     * @param string[] $files
     * @return SmartFileInfo[]
     */
    private function getSmartFileInfosFromStringFiles(array $files): array
    {
        $smartFileInfos = [];
        foreach ($files as $file) {
            $smartFileInfos[] = new SmartFileInfo($file);
        }

        return $smartFileInfos;
    }

    /**
     * @param string[] $source
     * @param string[] $suffixes
     * @return SmartFileInfo[]
     */
    private function collectFileInfos(array $source, array $suffixes): array
    {
        $files = $this->fileSystemFilter->filterFiles($source);
        $directories = $this->fileSystemFilter->filterDirectories($source);

        $smartFileInfos = [];
        foreach ($files as $file) {
            $smartFileInfos[] = new SmartFileInfo($file);
        }

        /**
         * @var SmartFileInfo[] $smartFileInfosInDirectories
         */
        $smartFileInfosInDirectories = $this->findInDirectories($directories, $suffixes);
        return array_merge($smartFileInfos, $smartFileInfosInDirectories);
    }

    /**
     * @param string[] $directories
     * @param string[] $suffixes
     * @return string[]|SmartFileInfo[]
     */
    private function findInDirectories(array $directories, array $suffixes, bool $isSmartFileInfos = true): array
    {
        if ($directories === []) {
            return [];
        }

        $suffixesPattern = $this->normalizeSuffixesToPattern($suffixes);

        $finder = Finder::create()
            ->followLinks()
            ->files()
            // skip empty files
            ->size('> 0')
            ->in($directories)
            ->name($suffixesPattern)
            ->sortByName();

        $this->addFilterWithExcludedPaths($finder);

        $smartFileInfos = $this->finderSanitizer->sanitize($finder);

        if ($isSmartFileInfos) {
            return $smartFileInfos;
        }

        $files = [];
        foreach ($smartFileInfos as $smartFileInfo) {
            $files[] = $smartFileInfo->getPathname();
        }

        return $files;
    }

    /**
     * @param string[] $suffixes
     */
    private function normalizeSuffixesToPattern(array $suffixes): string
    {
        $suffixesPattern = implode('|', $suffixes);
        return '#\.(' . $suffixesPattern . ')$#';
    }

    private function addFilterWithExcludedPaths(Finder $finder): void
    {
        $excludePaths = $this->skippedPathsResolver->resolve();
        if ($excludePaths === []) {
            return;
        }

        $finder->filter(function (SplFileInfo $splFileInfo) use ($excludePaths): bool {
            /** @var string|false $realPath */
            $realPath = $splFileInfo->getRealPath();
            if (! $realPath) {
                //dead symlink
                return false;
            }

            // make the path work accross different OSes
            $realPath = str_replace('\\', '/', $realPath);

            // return false to remove file
            foreach ($excludePaths as $excludePath) {
                // make the path work accross different OSes
                $excludePath = str_replace('\\', '/', $excludePath);

                if (Strings::match($realPath, '#' . preg_quote($excludePath, '#') . '#')) {
                    return false;
                }

                $excludePath = $this->normalizeForFnmatch($excludePath);
                if (fnmatch($excludePath, $realPath)) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * "value*" → "*value*"
     * "*value" → "*value*"
     */
    private function normalizeForFnmatch(string $path): string
    {
        // ends with *
        if (Strings::match($path, self::ENDS_WITH_ASTERISK_REGEX)) {
            return '*' . $path;
        }

        // starts with *
        if (Strings::match($path, self::STARTS_WITH_ASTERISK_REGEX)) {
            return $path . '*';
        }

        return $path;
    }
}
