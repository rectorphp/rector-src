<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\SmartFileSystem\Normalizer\PathNormalizer;

final class ClassMethodParamVendorLockResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private PathNormalizer $pathNormalizer,
        private ReflectionProvider $reflectionProvider,
        private PrivatesAccessor $privatesAccessor
    ) {
    }

    public function isVendorLocked(ClassMethod $classMethod): bool
    {
        if ($classMethod->isMagic()) {
            return true;
        }

        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        if ($this->hasTraitMethodVendorLock($classReflection, $methodName)) {
            return true;
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);
        foreach ($classReflection->getAncestors() as $ancestorClassReflection) {
            // skip self
            if ($ancestorClassReflection === $classReflection) {
                continue;
            }

            // parent type
            if (! $ancestorClassReflection->hasNativeMethod($methodName)) {
                continue;
            }

            // is file in vendor?
            $fileName = $ancestorClassReflection->getFileName();
            // probably internal class
            if ($fileName === false) {
                continue;
            }

            $normalizedFileName = $this->pathNormalizer->normalizePath($fileName);
            return str_contains($normalizedFileName, '/vendor/');
        }

        return false;
    }

    /**
     * @return ReflectionClass[]
     */
    private function findRelatedClassReflections(ClassReflection $classReflection): array
    {
        // @todo decouple to some reflection family finder?

        /** @var ReflectionClass[] $reflectionClasses */
        $reflectionClasses = $this->privatesAccessor->getPrivateProperty($this->reflectionProvider, 'classes');

        $relatedClassReflections = [];
        foreach ($reflectionClasses as $reflectionClass) {
            if ($reflectionClass->getName() === $classReflection->getName()) {
                continue;
            }

            // is related?
            if (! $reflectionClass->isSubclassOf($classReflection->getName())) {
                continue;
            }

            $relatedClassReflections[] = $reflectionClass;
        }

        return $relatedClassReflections;
    }

    private function hasTraitMethodVendorLock(ClassReflection $classReflection, string $methodName): bool
    {
        $relatedReflectionClasses = $this->findRelatedClassReflections($classReflection);

        foreach ($relatedReflectionClasses as $relatedReflectionClass) {
            foreach ($relatedReflectionClass->getTraits() as $traitClassReflection) {
                /** @var ClassReflection $traitClassReflection */
                if ($traitClassReflection->hasMethod($methodName)) {
                    return true;
                }
            }
        }

        return false;
    }
}
