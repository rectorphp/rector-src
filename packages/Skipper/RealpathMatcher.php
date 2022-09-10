<?php

declare(strict_types=1);

namespace Rector\Skipper;

final class RealpathMatcher
{
    public function match(string $matchingPath, string $filePath): bool
    {
        $normalizedMatchingPath = $this->normalizePath($matchingPath);
        $normalizedFilePath = $this->normalizePath($filePath);

        $realPathMatchingPath = realpath($normalizedMatchingPath);
        $realpathNormalizedFilePath = realpath($normalizedFilePath);

        if (! is_string($realPathMatchingPath)) {
            return false;
        }

        if (! is_string($realpathNormalizedFilePath)) {
            return false;
        }

        $realPathMatchingPath = $this->normalizePath($realPathMatchingPath);
        $realpathNormalizedFilePath = $this->normalizePath($realpathNormalizedFilePath);

        // skip define direct path
        if (is_file($realPathMatchingPath)) {
            return $realPathMatchingPath === $realpathNormalizedFilePath;
        }

        // ensure add / suffix to ensure no same prefix directory
        if (is_dir($realPathMatchingPath)) {
            $realPathMatchingPath = rtrim($realPathMatchingPath, '/') . '/';
        }

        return str_starts_with($realpathNormalizedFilePath, $realPathMatchingPath);
    }

    private function normalizePath(string $path): string
    {
        return \str_replace('\\', '/', $path);
    }
}
