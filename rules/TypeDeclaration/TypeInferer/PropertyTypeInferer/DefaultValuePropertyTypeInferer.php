<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use PhpParser\Node\Stmt\Property;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\NodeTypeResolver;

/**
 * Special case of type inferer - it is always added in the end of the resolved types
 */
final class DefaultValuePropertyTypeInferer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function inferProperty(Property $property): Type
    {
        $propertyProperty = $property->props[0];
        if ($propertyProperty->default === null) {
            return new MixedType();
        }

        return $this->nodeTypeResolver->getStaticType($propertyProperty->default);
    }

    public function getPriority(): int
    {
        return 100;
    }
}
