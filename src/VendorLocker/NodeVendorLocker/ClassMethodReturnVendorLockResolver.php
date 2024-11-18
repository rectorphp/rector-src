<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ExtendedFunctionVariant;
use PHPStan\Type\MixedType;
use Rector\NodeAnalyzer\MagicClassMethodAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Reflection\ReflectionResolver;

final readonly class ClassMethodReturnVendorLockResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ReflectionResolver $reflectionResolver,
        private MagicClassMethodAnalyzer $magicClassMethodAnalyzer
    ) {
    }

    public function isVendorLocked(ClassMethod $classMethod): bool
    {
        if ($this->magicClassMethodAnalyzer->isUnsafeOverridden($classMethod)) {
            return true;
        }

        if ($classMethod->isPrivate()) {
            return false;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);
        return $this->isVendorLockedByAncestors($classReflection, $methodName);
    }

    private function isVendorLockedByAncestors(ClassReflection $classReflection, string $methodName): bool
    {
        foreach ($classReflection->getAncestors() as $ancestorClassReflections) {
            if ($ancestorClassReflections === $classReflection) {
                continue;
            }

            $nativeClassReflection = $ancestorClassReflections->getNativeReflection();

            // this should avoid detecting @method as real method
            if (! $nativeClassReflection->hasMethod($methodName)) {
                continue;
            }

            if (! $ancestorClassReflections->hasNativeMethod($methodName)) {
                continue;
            }

            $parentClassMethodReflection = $ancestorClassReflections->getNativeMethod($methodName);
            $parametersAcceptor = $parentClassMethodReflection->getVariants()[0];
            if (! $parametersAcceptor instanceof ExtendedFunctionVariant) {
                continue;
            }

            // here we count only on strict types, not on docs
            return ! $parametersAcceptor->getNativeReturnType() instanceof MixedType;
        }

        return false;
    }
}
