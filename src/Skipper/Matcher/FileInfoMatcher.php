<?php

declare(strict_types=1);

namespace Rector\Skipper\Matcher;

use Rector\Skipper\FileSystem\FnMatchPathNormalizer;
use Rector\Skipper\FileSystem\PathNormalizer;
use Rector\Skipper\Fnmatcher;
use Rector\Skipper\RealpathMatcher;

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
        $filePath = PathNormalizer::normalize($filePath);
        foreach ($filePatterns as $filePattern) {
            $filePattern = PathNormalizer::normalize($filePattern);
            if ($this->doesFileMatchPattern($filePath, $filePattern)) {
                return true;
            }
        }

        return false;
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

        if (str_contains($ignoredPath, '*')) {
//            return false;
        }

        return $this->realpathMatcher->match($ignoredPath, $filePath);
    }
}
