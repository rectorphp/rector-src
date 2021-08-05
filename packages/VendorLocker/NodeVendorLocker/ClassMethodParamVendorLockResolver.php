<?php

declare(strict_types=1);

namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\SmartFileSystem\Normalizer\PathNormalizer;

final class ClassMethodParamVendorLockResolver
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private PathNormalizer $pathNormalizer
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

            dump($fileName);

            $normalizedFileName = $this->pathNormalizer->normalizePath($fileName);
            return str_contains($normalizedFileName, '/vendor/');
        }

        return false;
    }
}
