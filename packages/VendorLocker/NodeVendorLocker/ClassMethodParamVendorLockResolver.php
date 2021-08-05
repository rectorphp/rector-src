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

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        // @todo decouple to some reflection finder?
        /** @var ReflectionClass[] $reflectionClasses */
        $reflectionClasses = $this->privatesAccessor->getPrivateProperty($this->reflectionProvider, 'classes');

        foreach ($reflectionClasses as $reflectionClass) {
            if ($reflectionClass->getName() === $classReflection->getName()) {
                continue;
            }

            if (! $reflectionClass->isSubclassOf($classReflection->getName())) {
                continue;
            }

            // is related!
            dump($classReflection->getName());
            dump($reflectionClass->getName());
        }

        // build a family tree and check if there is a trait with the sam method in there
        dump(count($reflectionClasses));
        die;

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
}
