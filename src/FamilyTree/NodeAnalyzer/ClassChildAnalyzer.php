<?php

declare(strict_types=1);

namespace Rector\FamilyTree\NodeAnalyzer;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;

/**
 * @api
 */
final readonly class ClassChildAnalyzer
{
    /**
     * Look both parent class and interface, yes, all PHP interface methods are abstract
     *
     * @api rector-symfony
     */
    public function hasAbstractParentClassMethod(ClassReflection $classReflection, string $methodName): bool
    {
        $parentClassMethods = $this->resolveParentClassMethods($classReflection, $methodName);
        if ($parentClassMethods === []) {
            return false;
        }
<<<<<<< HEAD

<<<<<<< HEAD
        return array_any(
            $parentClassMethods,
            fn (PhpMethodReflection $phpMethodReflection): bool => $phpMethodReflection->isAbstract()
        );
=======
=======
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
        return array_any($parentClassMethods, fn ($parentClassMethod) => $parentClassMethod->isAbstract());
>>>>>>> 0ce3dbd107 ([php] bump to PHP 8.4 syntax)
    }

    /**
     * @api downgrade
     */
    public function resolveParentClassMethodReturnType(ClassReflection $classReflection, string $methodName): Type
    {
        $parentClassMethods = $this->resolveParentClassMethods($classReflection, $methodName);
        if ($parentClassMethods === []) {
            return new MixedType();
        }

        foreach ($parentClassMethods as $parentClassMethod) {
            $parametersAcceptor = ParametersAcceptorSelector::combineAcceptors($parentClassMethod->getVariants());
            $nativeReturnType = $parametersAcceptor->getNativeReturnType();

            if (! $nativeReturnType instanceof MixedType) {
                return $nativeReturnType;
            }
        }

        return new MixedType();
    }

    /**
     * @return PhpMethodReflection[]
     */
    private function resolveParentClassMethods(ClassReflection $classReflection, string $methodName): array
    {
        if ($classReflection->hasNativeMethod($methodName) && $classReflection->getNativeMethod(
            $methodName
        )->isPrivate()) {
            return [];
        }

        $parentClassMethods = [];
        $parents = [...$classReflection->getParents(), ...$classReflection->getInterfaces()];
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
