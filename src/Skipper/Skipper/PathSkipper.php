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
        $matchedPath = $this->fileInfoMatcher->matchPattern($filePath, $this->skippedPathsResolver->resolve());
        if ($matchedPath === null) {
            return false;
        }

        $this->usedSkipCollector->markUsed($matchedPath);
        return true;
    }
}
