<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use Rector\Skipper\Matcher\FileInfoMatcher;
use Rector\Skipper\SkipCriteriaResolver\SkippedPathsResolver;

final readonly class PathSkipper
{
    public function __construct(
        private FileInfoMatcher $fileInfoMatcher,
        private SkippedPathsResolver $skippedPathsResolver,
        private UsedSkipCollector $usedSkipCollector
    ) {
    }

    public function shouldSkip(string $filePath): bool
    {
        foreach ($this->skippedPathsResolver->resolve() as $skippedPath) {
            if ($this->fileInfoMatcher->doesFileInfoMatchPatterns($filePath, [$skippedPath])) {
                $this->usedSkipCollector->markUsed($skippedPath);
                return true;
            }
        }

        return false;
    }
}
