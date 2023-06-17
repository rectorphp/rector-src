<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\ClassLikeAstResolver;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\TypeDeclaration\TypeInferer\AssignToPropertyTypeInferer;

final class AllAssignNodePropertyTypeInferer
{
    public function __construct(
        private readonly AssignToPropertyTypeInferer $assignToPropertyTypeInferer,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ClassLikeAstResolver $classLikeAstResolver
    ) {
    }

    public function inferProperty(Property $property): ?Type
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($property);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $classLike = $this->classLikeAstResolver->resolveClassFromClassReflection($classReflection);
        $propertyName = $this->nodeNameResolver->getName($property);

        return $this->assignToPropertyTypeInferer->inferPropertyInClassLike($property, $propertyName, $classLike);
    }
}
