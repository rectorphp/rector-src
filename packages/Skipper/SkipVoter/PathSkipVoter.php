<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipVoter;

use Rector\Skipper\Contract\SkipVoterInterface;
use Rector\Skipper\Matcher\FileInfoMatcher;
use Rector\Skipper\SkipCriteriaResolver\SkippedPathsResolver;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PathSkipVoter implements SkipVoterInterface
{
    public function __construct(
        private readonly FileInfoMatcher $fileInfoMatcher,
        private readonly SkippedPathsResolver $skippedPathsResolver
    ) {
    }

    public function match(string | object $element): bool
    {
        return true;
    }

    public function shouldSkip(string | object $element, SmartFileInfo | string $file): bool
    {
        $skippedPaths = $this->skippedPathsResolver->resolve();
        return $this->fileInfoMatcher->doesFileInfoMatchPatterns($file, $skippedPaths);
    }
}
