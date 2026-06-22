<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipVoter;

use PHPStan\Reflection\ReflectionProvider;
use Rector\Skipper\SkipCriteriaResolver\SkippedClassResolver;
use Rector\Skipper\Skipper\SkipSkipper;
use Rector\Skipper\ValueObject\SkipMatch;

final readonly class ClassSkipVoter
{
    public function __construct(
        private SkipSkipper $skipSkipper,
        private SkippedClassResolver $skippedClassResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function match(string | object $element): bool
    {
        if (is_object($element)) {
            return true;
        }

        return $this->reflectionProvider->hasClass($element);
    }

    public function matchSkip(string | object $element, string $filePath): ?SkipMatch
    {
        $skippedClasses = $this->skippedClassResolver->resolve();
        return $this->skipSkipper->match($element, $filePath, $skippedClasses);
    }
}
