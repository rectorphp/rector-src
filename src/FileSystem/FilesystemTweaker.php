<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

use Nette\Utils\Strings;
use Symplify\SmartFileSystem\FileSystemGuard;

final class FilesystemTweaker
{
    public function __construct(
        private FileSystemGuard $fileSystemGuard
    ) {
    }

    /**
     * This will turn paths like "src/Symfony/Component/*\/Tests" to existing directory paths
     *
     * @param string[] $directories
     * @return string[]
     */
    public function resolveDirectoriesWithFnmatch(array $directories): array
    {
        $absoluteDirectories = [];
        foreach ($directories as $directory) {
            // is fnmatch for directories
            if (Strings::contains($directory, '*')) {
                $foundDirectories = $this->findDirectoriesInGlob($directory);
                $absoluteDirectories = array_merge($absoluteDirectories, $foundDirectories);
            } else {
                // is classic directory
                $this->fileSystemGuard->ensureDirectoryExists($directory);
                $absoluteDirectories[] = $directory;
            }
        }

        return $absoluteDirectories;
    }

    /**
     * This will turn paths like "src/Symfony/Component/*\/Tests" to existing directory paths
     *
     * @param string[] $paths
     *
     * @return string[]
     */
    public function resolveWithFnmatch(array $paths): array
    {
        $absolutePathsFound = [];
        foreach ($paths as $path) {
            if (Strings::contains($path, '*')) {
                $foundPaths = $this->foundInGlob($path);
                $absolutePathsFound = array_merge($absolutePathsFound, $foundPaths);
            } else {
                $absolutePathsFound[] = $path;
            }
        }

        return $absolutePathsFound;
    }

    /**
     * @return string[]
     */
    private function findDirectoriesInGlob(string $directory): array
    {
        $foundDirectories = [];

        foreach ((array) glob($directory, GLOB_ONLYDIR) as $foundDirectory) {
            if (! is_string($foundDirectory)) {
                continue;
            }

            $foundDirectories[] = $foundDirectory;
        }

        return $foundDirectories;
    }

    /**
     * @return string[]
     */
    private function foundInGlob(string $path): array
    {
        $foundPaths = [];

        foreach ((array) glob($path) as $foundPath) {
            if (! is_string($foundPath)) {
                continue;
            }

            if (! file_exists($foundPath)) {
                continue;
            }

            $foundPaths[] = $foundPath;
        }

        return $foundPaths;
    }
}
