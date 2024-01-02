<?php

declare(strict_types=1);

namespace Rector\Reflection;

use PhpParser\Node;
use PHPStan\Reflection\ClassReflection;

final readonly class ClassModifierChecker
{
    public function __construct(
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function isInsideFinalClass(Node $node): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->isFinalByKeyword();
    }

    public function isInsideAbstractClass(Node $node): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->isAbstract();
    }
}
