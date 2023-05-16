<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ErrorType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

/**
 * Utils for PropertyFetch Node:
 * "$this->property"
 */
final class MagicPropertyFetchAnalyzer
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function isMagicOnType(PropertyFetch $propertyFetch, ObjectType $objectType, Scope $scope): bool
    {
        $varNodeType = $this->nodeTypeResolver->getType($propertyFetch);
        if ($varNodeType instanceof ErrorType) {
            return true;
        }

        if ($varNodeType instanceof MixedType) {
            return false;
        }

        if ($varNodeType->isSuperTypeOf($objectType)->yes()) {
            return false;
        }

        $nodeName = $this->nodeNameResolver->getName($propertyFetch->name);
        if ($nodeName === null) {
            return false;
        }

        return ! $this->hasPublicProperty($propertyFetch, $nodeName, $scope);
    }

    private function hasPublicProperty(
        PropertyFetch | StaticPropertyFetch $expr,
        string $propertyName,
        Scope $scope
    ): bool {
        if ($expr instanceof PropertyFetch) {
            $propertyFetchType = $scope->getType($expr->var);
        } else {
            $propertyFetchType = $this->nodeTypeResolver->getType($expr->class);
        }

        if (! $propertyFetchType instanceof TypeWithClassName) {
            return false;
        }

        $propertyFetchType = $propertyFetchType->getClassName();
        if (! $this->reflectionProvider->hasClass($propertyFetchType)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($propertyFetchType);
        if (! $classReflection->hasProperty($propertyName)) {
            return false;
        }

        $propertyReflection = $classReflection->getProperty($propertyName, $scope);
        return $propertyReflection->isPublic();
    }
}
