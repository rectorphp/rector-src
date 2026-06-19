<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Reflection\ReflectionResolver;

final readonly class ClassMethodParamVendorLockResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ReflectionResolver $reflectionResolver,
    ) {
    }

    public function isVendorLocked(ClassMethod $classMethod): bool
    {
        if ($classMethod->isMagic()) {
            return true;
        }

        if ($classMethod->isPrivate()) {
            return false;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);

        // has interface vendor lock? → better skip it, as PHPStan has access only to just analyzed classes
        return $this->hasParentInterfaceMethod($classReflection, $methodName);
    }

    /**
     * Has interface even in our project?
     * Better skip it, as PHPStan has access only to just analyzed classes.
     * This might change type, that works for current class, but breaks another implementer.
     */
    private function hasParentInterfaceMethod(ClassReflection $classReflection, string $methodName): bool
    {
        return array_any(
            $classReflection->getInterfaces(),
<<<<<<< HEAD
            fn (ClassReflection $interfaceClassReflection): bool => $interfaceClassReflection->hasMethod($methodName)
=======
            fn ($interfaceClassReflection) => $interfaceClassReflection->hasMethod($methodName)
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
        );
    }
}
