<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Stmt\Property;
use Rector\NodeTypeResolver\NodeTypeResolver;
use PHPStan\Type\UnionType;
use PHPStan\Type\NullType;
use PHPStan\Type\CallableType;

final class PropertyAnalyzer
{
    public function __construct(private NodeTypeResolver $nodeTypeResolver)
    {
    }

    public function hasForbiddenType(Property $property): bool
    {
        $propertyType = $this->nodeTypeResolver->getType($property);
        if ($propertyType instanceof NullType) {
            return true;
        }

        if (! $propertyType instanceof UnionType) {
            return false;
        }

        $types = $propertyType->getTypes();
        foreach ($types as $type) {
            if ($type instanceof CallableType) {
                return true;
            }
        }

        return false;
    }
}
