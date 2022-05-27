<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

use Symplify\SmartFileSystem\FileSystemGuard;

final class FilesystemTweaker
{
    public function __construct(
        private readonly FileSystemGuard $fileSystemGuard
    ) {
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
            if (\str_contains($path, '*')) {
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
        /** @var string[] $foundDirectories */
        $foundDirectories = (array) glob($directory, GLOB_ONLYDIR);
        return $foundDirectories;
    }

    /**
     * @return string[]
     */
    private function foundInGlob(string $path): array
    {
        /** @var string[] $paths */
        $paths = (array) glob($path);

        return array_filter($paths, fn (string $path): bool => file_exists($path));
    }
}
