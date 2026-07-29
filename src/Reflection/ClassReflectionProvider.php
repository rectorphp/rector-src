<?php

declare(strict_types=1);

namespace Rector\Reflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;

/**
 * Narrow facade over PHPStan ReflectionProvider class lookups,
 * so rules do not have to depend on PHPStan reflection directly
 */
final readonly class ClassReflectionProvider
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    public function hasClass(string $className): bool
    {
        return $this->reflectionProvider->hasClass($className);
    }

    public function getClass(string $className): ClassReflection
    {
        return $this->reflectionProvider->getClass($className);
    }
}
