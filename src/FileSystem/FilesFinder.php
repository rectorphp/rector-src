<?php

declare(strict_types=1);

namespace Rector\FileSystem;

use Nette\Utils\FileSystem;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Caching\UnchangedFilesFilter;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Skipper\Skipper\PathSkipper;
use Rector\ValueObject\Configuration;
use Symfony\Component\Finder\Finder;

/**
 * @see \Rector\Tests\FileSystem\FilesFinder\FilesFinderTest
 */
final readonly class FilesFinder
{
    public function __construct(
        private FilesystemTweaker $filesystemTweaker,
        private UnchangedFilesFilter $unchangedFilesFilter,
        private FileAndDirectoryFilter $fileAndDirectoryFilter,
        private PathSkipper $pathSkipper,
        private FilePathHelper $filePathHelper,
        private ChangedFilesDetector $changedFilesDetector,
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

        // filtering files in files collection
        $filteredFilePaths = $this->fileAndDirectoryFilter->filterFiles($filesAndDirectories);
        $filteredFilePaths = array_map($this->filePathHelper->absolutePath(...), $filteredFilePaths);
        $filteredFilePaths = array_filter(
            $filteredFilePaths,
            fn (string $filePath): bool => ! $this->pathSkipper->shouldSkip($filePath)
        );

        if ($suffixes !== []) {
            $fileWithExtensionsFilter = static function (string $filePath) use ($suffixes): bool {
                $filePathExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                return in_array($filePathExtension, $suffixes, true);
            };
            $filteredFilePaths = array_filter($filteredFilePaths, $fileWithExtensionsFilter);
        }

        $filteredFilePaths = array_filter(
            $filteredFilePaths,
            function (string $file): bool {
                if ($this->isStartWithShortPHPTag(FileSystem::read($file))) {
                    SimpleParameterProvider::addParameter(
                        Option::SKIPPED_START_WITH_SHORT_OPEN_TAG_FILES,
                        $this->filePathHelper->relativePath($file)
                    );
                    return false;
                }

                return true;
            }
        );

        // filtering files in directories collection
        $directories = $this->fileAndDirectoryFilter->filterDirectories($filesAndDirectories);
        $filteredFilePathsInDirectories = $this->findInDirectories($directories, $suffixes, $sortByName);

        $filePaths = [...$filteredFilePaths, ...$filteredFilePathsInDirectories];
        return $this->unchangedFilesFilter->filterFilePaths($filePaths);
    }

    /**
     * @param string[] $paths
     * @return string[]
     */
    public function findFilesInPaths(array $paths, Configuration $configuration): array
    {
        if ($configuration->shouldClearCache()) {
            $this->changedFilesDetector->clear();
        }

        $supportedFileExtensions = $configuration->getFileExtensions();
        return $this->findInDirectoriesAndFiles($paths, $supportedFileExtensions);
    }

    /**
     * Exclude short "<?=" tags as lead to invalid changes
     */
    private function isStartWithShortPHPTag(string $fileContent): bool
    {
        return str_starts_with(ltrim($fileContent), '<?=');
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

            if ($this->isStartWithShortPHPTag($fileInfo->getContents())) {
                SimpleParameterProvider::addParameter(
                    Option::SKIPPED_START_WITH_SHORT_OPEN_TAG_FILES,
                    $this->filePathHelper->relativePath($path)
                );
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
