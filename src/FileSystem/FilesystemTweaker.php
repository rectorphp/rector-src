<?php

declare(strict_types=1);

namespace Rector\Core\FileSystem;

final class FilesystemTweaker
{
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
    private function foundInGlob(string $path): array
    {
        /** @var string[] $paths */
        $paths = (array) glob($path);

        return array_filter($paths, static fn (string $path): bool => file_exists($path));
    }
}
