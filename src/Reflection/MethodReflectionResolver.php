<?php

declare(strict_types=1);

namespace Rector\Reflection;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;

final readonly class MethodReflectionResolver
{
    public function __construct(
        private ClassReflectionProvider $classReflectionProvider
    ) {
    }

    /**
     * @param class-string $className
     */
    public function resolveMethodReflection(string $className, string $methodName, ?Scope $scope): ?MethodReflection
    {
        if (! $this->classReflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->classReflectionProvider->getClass($className);

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
