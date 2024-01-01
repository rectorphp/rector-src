<?php

declare(strict_types=1);

namespace Rector\FileSystem;

use Rector\Caching\UnchangedFilesFilter;
use Rector\Core\Util\StringUtils;
use Rector\Skipper\SkipCriteriaResolver\SkippedPathsResolver;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @see \Rector\Tests\FileSystem\FilesFinder\FilesFinderTest
 */
final class FilesFinder
{
    public function __construct(
        private readonly FilesystemTweaker $filesystemTweaker,
        private readonly SkippedPathsResolver $skippedPathsResolver,
        private readonly UnchangedFilesFilter $unchangedFilesFilter,
        private readonly FileAndDirectoryFilter $fileAndDirectoryFilter,
    ) {
    }

    /**
     * @param string[] $source
     * @param string[] $suffixes
     * @return string[]
     */
    public function findInDirectoriesAndFiles(array $source, array $suffixes = [], bool $sortByName = true): array
    {
        $filesAndDirectories = $this->filesystemTweaker->resolveWithFnmatch($source);

        $filePaths = $this->fileAndDirectoryFilter->filterFiles($filesAndDirectories);
        $directories = $this->fileAndDirectoryFilter->filterDirectories($filesAndDirectories);

        $currentAndDependentFilePaths = $this->unchangedFilesFilter->filterFileInfos($filePaths);

        return [...$currentAndDependentFilePaths, ...$this->findInDirectories($directories, $suffixes, $sortByName)];
    }

    /**
     * @param string[] $directories
     * @param string[] $suffixes
     * @return string[]
     */
    private function findInDirectories(array $directories, array $suffixes, bool $sortByName = true): array
    {
        if ($directories === []) {
            return [];
        }

        $finder = Finder::create()
            ->files()
            // skip empty files
            ->size('> 0')
            ->in($directories);

        if ($sortByName) {
            $finder->sortByName();
        }

        if ($suffixes !== []) {
            $suffixesPattern = $this->normalizeSuffixesToPattern($suffixes);
            $finder->name($suffixesPattern);
        }

        $this->addFilterWithExcludedPaths($finder);

        $filePaths = [];
        foreach ($finder as $fileInfo) {
            // getRealPath() function will return false when it checks broken symlinks.
            // So we should check if this file exists or we got broken symlink

            /** @var string|false $path */
            $path = $fileInfo->getRealPath();
            if ($path !== false) {
                $filePaths[] = $path;
            }
        }

        return $this->unchangedFilesFilter->filterFileInfos($filePaths);
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
            if ($realPath === false) {
                // dead symlink
                return false;
            }

            // make the path work accross different OSes
            $realPath = str_replace('\\', '/', $realPath);

            // return false to remove file
            foreach ($excludePaths as $excludePath) {
                // make the path work accross different OSes
                $excludePath = str_replace('\\', '/', $excludePath);

                if (fnmatch($this->normalizeForFnmatch($excludePath), $realPath)) {
                    return false;
                }

                if (str_contains($excludePath, '**')) {
                    // prevent matching a fnmatch pattern as a regex
                    // which is a waste of resources
                    continue;
                }

                if (StringUtils::isMatch($realPath, '#' . preg_quote($excludePath, '#') . '#')) {
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
        if (str_ends_with($path, '*') || str_starts_with($path, '*')) {
            return '*' . trim($path, '*') . '*';
        }

        return $path;
    }
}
