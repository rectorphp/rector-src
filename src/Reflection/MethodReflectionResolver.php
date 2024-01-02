<?php

declare(strict_types=1);

namespace Rector\Reflection;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;

final readonly class MethodReflectionResolver
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @param class-string $className
     */
    public function resolveMethodReflection(string $className, string $methodName, ?Scope $scope): ?MethodReflection
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        // better, with support for "@method" annotation methods
        if ($scope instanceof Scope) {
            if ($classReflection->hasMethod($methodName)) {
                return $classReflection->getMethod($methodName, $scope);
            }
        } elseif ($classReflection->hasNativeMethod($methodName)) {
            return $classReflection->getNativeMethod($methodName);
        }

        return null;
    }
}
