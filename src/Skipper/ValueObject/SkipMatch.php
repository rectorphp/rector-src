<?php

declare(strict_types=1);

namespace Rector\Skipper\ValueObject;

/**
 * Result of a matched class/path skip: the skipped class and the concrete path pattern
 * that matched, if the skip is scoped to specific paths.
 */
final readonly class SkipMatch
{
    public function __construct(
        private string $skippedClass,
        private ?string $matchedPath
    ) {
    }

    public function getSkippedClass(): string
    {
        return $this->skippedClass;
    }

    public function getMatchedPath(): ?string
    {
        return $this->matchedPath;
    }
}
