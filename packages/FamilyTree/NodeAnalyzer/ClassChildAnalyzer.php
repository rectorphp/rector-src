<?php

declare(strict_types=1);

namespace Rector\FamilyTree\NodeAnalyzer;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;

final class ClassChildAnalyzer
{
    public function __construct(
        private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer
    ) {
    }

    public function hasChildClassMethod(ClassReflection $classReflection, string $methodName): bool
    {
        $childrenClassReflections = $this->familyRelationsAnalyzer->getChildrenOfClassReflection($classReflection);

        foreach ($childrenClassReflections as $childClassReflection) {
            if (! $childClassReflection->hasNativeMethod($methodName)) {
                continue;
            }

            $constructorReflectionMethod = $childClassReflection->getNativeMethod($methodName);
            if (! $constructorReflectionMethod instanceof PhpMethodReflection) {
                continue;
            }

            $methodDeclaringClassReflection = $constructorReflectionMethod->getDeclaringClass();
            if ($methodDeclaringClassReflection->getName() === $childClassReflection->getName()) {
                return true;
            }
        }

        return false;
    }

    public function hasParentClassMethod(ClassReflection $classReflection, string $methodName): bool
    {
        return $this->resolveParentClassMethods($classReflection, $methodName) !== [];
    }

    /**
     * Look both parent class and interface, yes, all PHP interface methods are abstract
     */
    public function hasAbstractParentClassMethod(ClassReflection $classReflection, string $methodName): bool
    {
        $parentClassMethods = $this->resolveParentClassMethods($classReflection, $methodName);
        if ($parentClassMethods === []) {
            return false;
        }

        foreach ($parentClassMethods as $parentClassMethod) {
            if ($parentClassMethod->isAbstract()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return PhpMethodReflection[]
     */
    private function resolveParentClassMethods(ClassReflection $classReflection, string $methodName): array
    {
        $parentClassMethods = [];
        $parents = array_merge($classReflection->getParents(), $classReflection->getInterfaces());
        foreach ($parents as $parent) {
            if (! $parent->hasNativeMethod($methodName)) {
                continue;
            }

            $methodReflection = $parent->getNativeMethod($methodName);
            if (! $methodReflection instanceof PhpMethodReflection) {
                continue;
            }

            $methodDeclaringMethodClass = $methodReflection->getDeclaringClass();
            if ($methodDeclaringMethodClass->getName() === $parent->getName()) {
                $parentClassMethods[] = $methodReflection;
            }
        }

        return $parentClassMethods;
    }
}
