<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipVoter;

use PHPStan\Reflection\ReflectionProvider;
use Rector\Skipper\Contract\SkipVoterInterface;
use Rector\Skipper\SkipCriteriaResolver\SkippedClassResolver;
use Rector\Skipper\Skipper\SkipSkipper;

final class ClassSkipVoter implements SkipVoterInterface
{
    public function __construct(
        private readonly SkipSkipper $skipSkipper,
        private readonly SkippedClassResolver $skippedClassResolver,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function match(string | object $element): bool
    {
        if (is_object($element)) {
            return true;
        }

        return $this->reflectionProvider->hasClass($element);
    }

    public function shouldSkip(string | object $element, string $filePath): bool
    {
        $skippedClasses = $this->skippedClassResolver->resolve();
        return $this->skipSkipper->doesMatchSkip($element, $filePath, $skippedClasses);
    }
}
