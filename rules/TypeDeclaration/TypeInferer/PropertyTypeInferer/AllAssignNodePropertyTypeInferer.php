<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\ClassLikeAstResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\TypeDeclaration\TypeInferer\AssignToPropertyTypeInferer;

final class AllAssignNodePropertyTypeInferer
{
    public function __construct(
        private readonly AssignToPropertyTypeInferer $assignToPropertyTypeInferer,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly AstResolver $astResolver
    ) {
    }

    public function inferProperty(Property $property, ClassReflection $classReflection): ?Type
    {
        /** @var ClassLike $classLike */
        $classLike = $this->astResolver->resolveClassFromClassReflection($classReflection);
        $propertyName = $this->nodeNameResolver->getName($property);

        return $this->assignToPropertyTypeInferer->inferPropertyInClassLike($property, $propertyName, $classLike);
    }
}
