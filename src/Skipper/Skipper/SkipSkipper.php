<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use Rector\Skipper\Matcher\FileInfoMatcher;

final readonly class SkipSkipper
{
    public function __construct(
        private FileInfoMatcher $fileInfoMatcher,
        private UsedSkipCollector $usedSkipCollector
    ) {
    }

    /**
     * @param array<string, string[]|null> $skippedClasses
     */
    public function doesMatchSkip(object | string $checker, string $filePath, array $skippedClasses): bool
    {
        foreach ($skippedClasses as $skippedClass => $skippedFiles) {
            if (! is_a($checker, $skippedClass, true)) {
                continue;
            }

            // skip everywhere
            if (! is_array($skippedFiles)) {
                $this->usedSkipCollector->markUsed($skippedClass);
                return true;
            }

            // mark the specific matched path used, so unused paths under the same rule are reported
            $matchedPath = $this->fileInfoMatcher->matchPattern($filePath, $skippedFiles);
            if ($matchedPath !== null) {
                $this->usedSkipCollector->markUsed($matchedPath);
                return true;
            }
        }

        return false;
    }
}
