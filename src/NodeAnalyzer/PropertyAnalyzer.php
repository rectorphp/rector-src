<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Stmt\Property;
use PHPStan\Type\CallableType;
use PHPStan\Type\NullType;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class PropertyAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function hasForbiddenType(Property $property): bool
    {
        $propertyType = $this->nodeTypeResolver->getType($property);
        $types = $propertyType instanceof UnionType
            ? $propertyType->getTypes()
            : [$propertyType];

        $totalTypes = count($types);
        foreach ($types as $type) {
            // when types === 2 and nullable, it already handled in Nullable type check
            // to convert with ?TheType
            if ($totalTypes !== 2 && $type instanceof NullType) {
                return true;
            }

            if ($type instanceof CallableType) {
                return true;
            }
        }

        return false;
    }
}
