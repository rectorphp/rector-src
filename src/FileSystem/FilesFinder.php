<?php

declare(strict_types=1);

namespace Rector\FileSystem;

use Nette\Utils\FileSystem;
use Rector\Caching\UnchangedFilesFilter;
use Rector\Skipper\Skipper\PathSkipper;
use Symfony\Component\Finder\Finder;

/**
 * @see \Rector\Tests\FileSystem\FilesFinder\FilesFinderTest
 */
final readonly class FilesFinder
{
    /**
     * @var string
     * @see https://regex101.com/r/3NwDLo/1
     */
    private const OPEN_SHORTTAG_REGEX = '#^\<\?=#';

    public function __construct(
        private FilesystemTweaker $filesystemTweaker,
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

        $files = $this->fileAndDirectoryFilter->filterFiles($filesAndDirectories);

        // exclude short "<?=" tags as lead to invalid changes
        $files = array_filter($files, static function (string $file): bool {
            $fileContents = FileSystem::read($file);
            return ! str_starts_with($fileContents, '<?=');
        });

        $filteredFilePaths = array_filter(
            $files,
            fn (string $filePath): bool => ! $this->pathSkipper->shouldSkip($filePath)
        );

        if ($suffixes !== []) {
            $fileWithExtensionsFilter = static function (string $filePath) use ($suffixes): bool {
                $filePathExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                return in_array($filePathExtension, $suffixes, true);
            };
            $filteredFilePaths = array_filter($filteredFilePaths, $fileWithExtensionsFilter);
        }

        $directories = $this->fileAndDirectoryFilter->filterDirectories($filesAndDirectories);
        $filteredFilePathsInDirectories = $this->findInDirectories($directories, $suffixes, $sortByName);
        $filePaths = [...$filteredFilePaths, ...$filteredFilePathsInDirectories];

        return $this->unchangedFilesFilter->filterFilePaths($filePaths);
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
            // changes in PHP files with short echo tag will mostly create invalid code, "<?php" tags are required
            ->notContains(self::OPEN_SHORTTAG_REGEX)
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

        return $filePaths;
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
