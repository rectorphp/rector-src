<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Symplify\SmartFileSystem\Normalizer\PathNormalizer;

final class ClassMethodParamVendorLockResolver
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PathNormalizer $pathNormalizer,
        private readonly FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    /**
     * Includes non-vendor classes
     */
    public function isSoftLocked(ClassMethod $classMethod): bool
    {
        if ($this->isVendorLocked($classMethod)) {
            return true;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);
        return $this->hasClassMethodLockMatchingFileName($classReflection, $methodName, '');
    }

    public function isVendorLocked(ClassMethod $classMethod): bool
    {
        if ($classMethod->isMagic()) {
            return true;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);
        if ($this->hasTraitMethodVendorLock($classReflection, $methodName)) {
            return true;
        }

        // has interface vendor lock? → better skip it, as PHPStan has access only to just analyzed classes
        if ($this->hasParentInterfaceMethod($classReflection, $methodName)) {
            return true;
        }

        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);

        return $this->hasClassMethodLockMatchingFileName($classReflection, $methodName, '/vendor/');
    }

    private function hasTraitMethodVendorLock(ClassReflection $classReflection, string $methodName): bool
    {
        $relatedReflectionClasses = $this->familyRelationsAnalyzer->getChildrenOfClassReflection($classReflection);

        foreach ($relatedReflectionClasses as $relatedReflectionClass) {
            foreach ($relatedReflectionClass->getTraits() as $traitReflectionClass) {
                /** @var ClassReflection $traitReflectionClass */
                if ($traitReflectionClass->hasMethod($methodName)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Has interface even in our project?
     * Better skip it, as PHPStan has access only to just analyzed classes.
     * This might change type, that works for current class, but breaks another implementer.
     */
    private function hasParentInterfaceMethod(ClassReflection $classReflection, string $methodName): bool
    {
        foreach ($classReflection->getInterfaces() as $interfaceClassReflection) {
            if ($interfaceClassReflection->hasMethod($methodName)) {
                return true;
            }
        }

        return false;
    }

    private function hasClassMethodLockMatchingFileName(
        ClassReflection $classReflection,
        string $methodName,
        string $filePathPartName
    ): bool {
        $ancestorClassReflections = array_merge($classReflection->getParents(), $classReflection->getInterfaces());
        foreach ($ancestorClassReflections as $ancestorClassReflection) {
            // parent type
            if (! $ancestorClassReflection->hasNativeMethod($methodName)) {
                continue;
            }

            // is file in vendor?
            $fileName = $ancestorClassReflection->getFileName();
            // probably internal class
            if ($fileName === null) {
                continue;
            }

            // not conditions? its a match
            if ($filePathPartName === '') {
                return true;
            }

            $normalizedFileName = $this->pathNormalizer->normalizePath($fileName);
            if (str_contains($normalizedFileName, $filePathPartName)) {
                return true;
            }
        }

        return false;
    }
}
