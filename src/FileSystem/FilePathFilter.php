<?php

declare(strict_types=1);

namespace Rector\FileSystem;

/**
 * Keeps only files matching all given --filter patterns.
 *
 * @see \Rector\Tests\FileSystem\FilePathFilter\FilePathFilterTest
 */
final class FilePathFilter
{
    private const string TESTS_KEYWORD = 'tests';

    /**
     * Splits a comma-separated --filter value into individual patterns, trimming blanks.
     *
     * @return string[]
     */
    public function parsePatterns(string $rawFilter): array
    {
        $patterns = [];
        foreach (explode(',', $rawFilter) as $pattern) {
            $pattern = trim($pattern);
            if ($pattern !== '') {
                $patterns[] = $pattern;
            }
        }

        return $patterns;
    }

    /**
     * Keeps only files that match every pattern (AND). With no patterns the input is returned unchanged.
     *
     * @param string[] $filePaths
     * @param string[] $patterns
     * @return string[]
     */
    public function filter(array $filePaths, array $patterns): array
    {
        if ($patterns === []) {
            return $filePaths;
        }

        return array_values(array_filter(
            $filePaths,
            fn (string $filePath): bool => $this->matchesAllPatterns($filePath, $patterns)
        ));
    }

    /**
     * @param string[] $patterns
     */
    private function matchesAllPatterns(string $filePath, array $patterns): bool
    {
        return array_all($patterns, fn (string $pattern): bool => $this->matchesPattern($filePath, $pattern));
    }

    /**
     * Three kinds of pattern are recognised:
     *  - "tests"           the basename ends in Test.php or TestCase.php
     *  - contains "*"      glob matched against the basename, e.g. *Repository.php
     *  - anything else     substring matched anywhere in the full path, e.g. /Controller/
     */
    private function matchesPattern(string $filePath, string $pattern): bool
    {
        $basename = basename($filePath);

        if ($pattern === self::TESTS_KEYWORD) {
            return str_ends_with($basename, 'Test.php') || str_ends_with($basename, 'TestCase.php');
        }

        if (str_contains($pattern, '*')) {
            return fnmatch($pattern, $basename);
        }

        return str_contains($filePath, $pattern);
    }
}
