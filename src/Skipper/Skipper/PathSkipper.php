<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use Rector\Skipper\Matcher\FileInfoMatcher;
use Rector\Skipper\SkipCriteriaResolver\SkippedPathsResolver;

final class PathSkipper
{
    public function __construct(
        private readonly FileInfoMatcher $fileInfoMatcher,
        private readonly SkippedPathsResolver $skippedPathsResolver
    ) {
    }

    public function shouldSkip(string $filePath): bool
    {
        $skippedPaths = $this->skippedPathsResolver->resolve();
        return $this->fileInfoMatcher->doesFileInfoMatchPatterns(
            $filePath,
            $skippedPaths
        );
    }
}
