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
    ) {
    }

    public function matchSkippedPath(string $filePath): ?string
    {
        return $this->fileInfoMatcher->matchPattern($filePath, $this->skippedPathsResolver->resolve());
    }
}
