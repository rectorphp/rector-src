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
        private ClassAnalyzer $classAnalyzer,
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function resolveAncestorClassReflectionOverrideableMethod(
        ClassLike $classLike,
        ClassMethod $classMethod
    ): ?ClassReflection
    {
        if (! $this->classAnalyzer->isAnonymousClass($classLike)) {
            return null;
        }

        $interfaces = $classLike->implements;
        foreach ($interfaces as $interface) {
            if (! $interface instanceof FullyQualified) {
                continue;
            }

            if ($this->isFoundNotPrivateMethod($interface, $classMethod)) {
                return $this->reflectionProvider->getClass($interface->toString());
            }
        }

        /** @var Class_ $classLike */
        if (! $classLike->extends instanceof FullyQualified) {
            return null;
        }

        if ($this->isFoundNotPrivateMethod($classLike->extends, $classMethod)) {
            return $this->reflectionProvider->getClass($classLike->extends->toString());
        }

        return null;
    }

    private function isFoundNotPrivateMethod(FullyQualified $fullyQualified, ClassMethod $classMethod): bool
    {
        $ancestorClassLike = $fullyQualified->toString();
        if (! $this->reflectionProvider->hasClass($ancestorClassLike)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($ancestorClassLike);
        $methodName = $this->nodeNameResolver->getName($classMethod);

        if (! $classReflection->hasMethod($methodName)) {
            return false;
        }

        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        $method = $classReflection->getMethod($methodName, $scope);

        if (! $method instanceof PhpMethodReflection) {
            return false;
        }

        return ! $method->isPrivate();
    }
}
