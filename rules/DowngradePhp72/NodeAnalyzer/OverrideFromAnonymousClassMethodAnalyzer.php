<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\NodeAnalyzer;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class OverrideFromAnonymousClassMethodAnalyzer
{
    public function __construct(
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function matchAncestorClassReflectionOverrideable(
        ClassLike $classLike,
        ClassMethod $classMethod
    ): ?ClassReflection {
        if (! $this->classAnalyzer->isAnonymousClass($classLike)) {
            return null;
        }

        /** @var Class_ $classLike */
        $interfaces = $classLike->implements;
        foreach ($interfaces as $interface) {
            if (! $interface instanceof FullyQualified) {
                continue;
            }

            $resolve = $this->resolveClassReflectionWithNotPrivateMethod($interface, $classMethod);
            if ($resolve instanceof ClassReflection) {
                return $resolve;
            }
        }

        /** @var Class_ $classLike */
        if (! $classLike->extends instanceof FullyQualified) {
            return null;
        }

        return $this->resolveClassReflectionWithNotPrivateMethod($classLike->extends, $classMethod);
    }

    private function resolveClassReflectionWithNotPrivateMethod(
        FullyQualified $fullyQualified,
        ClassMethod $classMethod
    ): ?ClassReflection {
        $ancestorClassLike = $fullyQualified->toString();
        if (! $this->reflectionProvider->hasClass($ancestorClassLike)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($ancestorClassLike);
        $methodName = $this->nodeNameResolver->getName($classMethod);
        if (! $classReflection->hasMethod($methodName)) {
            return null;
        }

        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        $method = $classReflection->getMethod($methodName, $scope);
        if (! $method instanceof PhpMethodReflection) {
            return null;
        }

        if ($method->isPrivate()) {
            return null;
        }

        return $classReflection;
    }
}
