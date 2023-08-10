<?php

declare(strict_types=1);

namespace Rector\Core\Reflection;

use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use ReflectionEnum;

final class ClassReflectionAnalyzer
{
    public function __construct(
        private readonly PrivatesAccessor $privatesAccessor
    ) {
    }

    public function resolveParentClassName(ClassReflection $classReflection): ?string
    {
        $nativeReflection = $classReflection->getNativeReflection();
        if ($nativeReflection instanceof ReflectionEnum) {
            return null;
        }

        $betterReflectionClass = $this->privatesAccessor->getPrivateProperty(
            $nativeReflection,
            'betterReflectionClass'
        );
        /** @var ReflectionClass $betterReflectionClass */
        return $betterReflectionClass->getParentClassName();
    }
}
