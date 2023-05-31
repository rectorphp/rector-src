<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeNameResolver\NodeNameResolver;

final class ClassMethodManipulator
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function isNamedConstructor(ClassMethod $classMethod): bool
    {
        if (! $this->nodeNameResolver->isName($classMethod, MethodName::CONSTRUCT)) {
            return false;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (!$classReflection instanceof ClassReflection) {
            return false;
        }

        if ($classMethod->isPrivate()) {
            return true;
        }

        if ($classReflection->isFinalByKeyword()) {
            return false;
        }

        return $classMethod->isProtected();
    }

    public function hasParentMethodOrInterfaceMethod(Class_ $class, string $methodName): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($class);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        foreach ($classReflection->getParents() as $parentClassReflection) {
            if ($parentClassReflection->hasMethod($methodName)) {
                return true;
            }
        }

        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            if ($interfaceReflection->hasMethod($methodName)) {
                return true;
            }
        }

        return false;
    }
}
