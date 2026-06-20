<?php

declare(strict_types=1);

namespace Rector\Skipper\Matcher;

use Rector\Skipper\FileSystem\FnMatchPathNormalizer;
use Rector\Skipper\FileSystem\PathNormalizer;
use Rector\Skipper\Fnmatcher;
use Rector\Skipper\RealpathMatcher;

/**
 * @see \Rector\Tests\Skipper\Matcher\FileInfoMatcherTest
 */
final readonly class FileInfoMatcher
{
    public function __construct(
        private FnMatchPathNormalizer $fnMatchPathNormalizer,
        private Fnmatcher $fnmatcher,
        private RealpathMatcher $realpathMatcher
    ) {
    }

    /**
     * @param string[] $filePatterns
     */
    public function doesFileInfoMatchPatterns(string $filePath, array $filePatterns): bool
    {
        return $this->matchPattern($filePath, $filePatterns) !== null;
    }

    /**
     * Returns the original (un-normalized) pattern that matched, so callers can report the exact
     * configured path. Returns null when no pattern matches.
     *
     * @param string[] $filePatterns
     */
    public function matchPattern(string $filePath, array $filePatterns): ?string
    {
        $normalizedFilePath = PathNormalizer::normalize($filePath);
        foreach ($filePatterns as $filePattern) {
            $normalizedFilePattern = PathNormalizer::normalize($filePattern);
            if ($this->doesFileMatchPattern($normalizedFilePath, $normalizedFilePattern)) {
                return $filePattern;
            }
        }

        return null;
    }

    /**
     * Supports both relative and absolute $file path. They differ for PHP-CS-Fixer and PHP_CodeSniffer.
     */
    private function doesFileMatchPattern(string $filePath, string $ignoredPath): bool
    {
        // in rector.php, the path can be absolute
        if ($filePath === $ignoredPath) {
            return true;
        }

        $ignoredPath = $this->fnMatchPathNormalizer->normalizeForFnmatch($ignoredPath);
        if ($ignoredPath === '') {
            return false;
        }

        if (str_starts_with($filePath, $ignoredPath)) {
            return true;
        }

        if (str_ends_with($filePath, $ignoredPath)) {
            return true;
        }

        if ($this->fnmatcher->match($ignoredPath, $filePath)) {
            return true;
        }

        return $this->realpathMatcher->match($ignoredPath, $filePath);
    }
}
