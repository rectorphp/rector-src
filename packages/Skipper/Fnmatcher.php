<?php

declare(strict_types=1);

namespace Rector\Skipper;

final class Fnmatcher
{
    public function match(string $matchingPath, string $filePath): bool
    {
        $normalizedMatchingPath = $this->normalizePath($matchingPath);
        $normalizedFilePath = $this->normalizePath($filePath);
        if (\fnmatch($normalizedMatchingPath, $normalizedFilePath)) {
            return \true;
        }

        $realPathMatchingPath = realpath($normalizedMatchingPath);
        $realpathNormalizedFilePath = realpath($normalizedFilePath);

        if (! is_string($realPathMatchingPath)) {
            // in case of relative compare
            return \fnmatch('*/' . $normalizedMatchingPath, $normalizedFilePath);
        }

        if (! is_string($realpathNormalizedFilePath)) {
            // in case of relative compare
            return \fnmatch('*/' . $normalizedMatchingPath, $normalizedFilePath);
        }

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
