<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\Type;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\TypeDeclaration\TypeInferer\AssignToPropertyTypeInferer;

final class AllAssignNodePropertyTypeInferer
{
    public function __construct(
        private readonly AssignToPropertyTypeInferer $assignToPropertyTypeInferer,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function inferProperty(ClassLike $classLike, Property $property): ?Type
    {
        $propertyName = $this->nodeNameResolver->getName($property);
        return $this->assignToPropertyTypeInferer->inferPropertyInClassLike($property, $propertyName, $classLike);
    }
}
