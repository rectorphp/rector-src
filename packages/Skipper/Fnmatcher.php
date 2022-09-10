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
        $realpathNormalizedPath = realpath($normalizedFilePath);
        if (! is_string($realPathMatchingPath)) {
            // in case of relative compare
            return \fnmatch('*/' . $normalizedMatchingPath, $normalizedFilePath);
        }
        if (! is_string($realpathNormalizedPath)) {
            // in case of relative compare
            return \fnmatch('*/' . $normalizedMatchingPath, $normalizedFilePath);
        }
        return str_starts_with($realpathNormalizedPath, $realPathMatchingPath);
    }

    private function normalizePath(string $path): string
    {
        return \str_replace('\\', '/', $path);
    }
}
