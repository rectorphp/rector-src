<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;

final readonly class SkipSkipper
{
    /**
     * @param array<string, FileNodeSkipperInterface[]|null> $skippedClasses
     */
    public function doesMatchSkip(object | string $checker, string $filePath, ?Node $node, array $skippedClasses): bool
    {
        foreach ($skippedClasses as $skippedClass => $skippedFiles) {
            if (! is_a($checker, $skippedClass, true)) {
                continue;
            }

            // skip everywhere
            if (! is_array($skippedFiles)) {
                return true;
            }

            foreach ($skippedFiles as $skippedFile) {
                if ($skippedFile->shouldSkip($filePath, $node)) {
                    return true;
                }
            }
        }

        return false;
    }
}
