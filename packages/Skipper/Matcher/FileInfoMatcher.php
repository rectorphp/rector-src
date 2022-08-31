<?php

declare(strict_types=1);

namespace Rector\Skipper\Matcher;

use Rector\Skipper\FileSystem\FnMatchPathNormalizer;
use Rector\Skipper\Fnmatcher;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FileInfoMatcher
{
    public function __construct(
        private readonly FnMatchPathNormalizer $fnMatchPathNormalizer,
        private readonly Fnmatcher $fnmatcher
    ) {
    }

    /**
     * @param string[] $filePatterns
     */
    public function doesFileInfoMatchPatterns(SmartFileInfo | string $file, array $filePatterns): bool
    {
        foreach ($filePatterns as $filePattern) {
            if ($this->doesFileMatchPattern($file, $filePattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Supports both relative and absolute $file path. They differ for PHP-CS-Fixer and PHP_CodeSniffer.
     */
    private function doesFileMatchPattern(SmartFileInfo | string $file, string $ignoredPath): bool
    {
        $filePath = $file instanceof SmartFileInfo ? $file->getRealPath() : $file;

        // in ecs.php, the path can be absolute
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

        return $this->fnmatcher->match($ignoredPath, $filePath);
    }
}
