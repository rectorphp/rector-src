<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipCriteriaResolver;

use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Configuration\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class SkippedClassResolver
{
    /**
     * @var array<string, string[]|null>
     */
    private array $skippedClasses = [];

    public function __construct(
        private readonly ParameterProvider $parameterProvider,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @return array<string, string[]|null>
     */
    public function resolve(): array
    {
        if ($this->skippedClasses !== []) {
            return $this->skippedClasses;
        }

        $skip = $this->parameterProvider->provideArrayParameter(Option::SKIP);

        foreach ($skip as $key => $value) {
            // e.g. [SomeClass::class] → shift values to [SomeClass::class => null]
            if (is_int($key)) {
                $key = $value;
                $value = null;
            }

            if (! is_string($key)) {
                continue;
            }

            if (! $this->reflectionProvider->hasClass($key)) {
                continue;
            }

            $this->skippedClasses[$key] = $value;
        }

        return $this->skippedClasses;
    }
}
