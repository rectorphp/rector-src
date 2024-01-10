<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Type\MixedType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Reflection\ReflectionResolver;

final readonly class ClassMethodReturnVendorLockResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function isVendorLocked(ClassMethod $classMethod): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);
        return $this->isVendorLockedByAncestors($classReflection, $methodName);
    }

    private function isVendorLockedByAncestors(ClassReflection $classReflection, string $methodName): bool
    {
        $ancestorClassReflections = [...$classReflection->getParents(), ...$classReflection->getInterfaces()];
        foreach ($ancestorClassReflections as $ancestorClassReflection) {
            $nativeClassReflection = $ancestorClassReflection->getNativeReflection();

            // this should avoid detecting @method as real method
            if (! $nativeClassReflection->hasMethod($methodName)) {
                continue;
            }

            $parentClassMethodReflection = $ancestorClassReflection->getNativeMethod($methodName);
            $parametersAcceptor = $parentClassMethodReflection->getVariants()[0];
            if (! $parametersAcceptor instanceof FunctionVariantWithPhpDocs) {
                continue;
            }

            // here we count only on strict types, not on docs
            return ! $parametersAcceptor->getNativeReturnType() instanceof MixedType;
        }

        return false;
    }
}
