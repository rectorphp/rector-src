<?php

declare(strict_types=1);

namespace Rector\FileSystem;

use Rector\Caching\UnchangedFilesFilter;
use Rector\Skipper\SkipCriteriaResolver\SkippedPathsResolver;
use Rector\Skipper\Skipper\PathSkipper;
use Symfony\Component\Finder\Finder;

/**
 * @see \Rector\Tests\FileSystem\FilesFinder\FilesFinderTest
 */
final readonly class FilesFinder
{
    public function __construct(
        private FilesystemTweaker $filesystemTweaker,
        private SkippedPathsResolver $skippedPathsResolver,
        private UnchangedFilesFilter $unchangedFilesFilter,
        private FileAndDirectoryFilter $fileAndDirectoryFilter,
        private PathSkipper $pathSkipper,
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
        $filePaths = array_filter($filePaths, fn (string $filePath): bool => ! $this->pathSkipper->shouldSkip($filePath));
        $currentAndDependentFilePaths = $this->unchangedFilesFilter->filterFileInfos($filePaths);

        $directories = $this->fileAndDirectoryFilter->filterDirectories($filesAndDirectories);
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

        $filePaths = [];
        foreach ($finder as $fileInfo) {
            // getRealPath() function will return false when it checks broken symlinks.
            // So we should check if this file exists or we got broken symlink

            /** @var string|false $path */
            $path = $fileInfo->getRealPath();
            if ($path === false) {
                continue;
            }

            if ($this->pathSkipper->shouldSkip($path)) {
                continue;
            }

            $filePaths[] = $path;
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
}
