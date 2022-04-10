<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PHPStan\Type\NullType;
use PHPStan\Type\UnionType;

final class UnionTypeAnalyzer
{
    public function isUnionTypeContainingNullType(UnionType $propertyType): bool
    {
        foreach ($propertyType->getTypes() as $subType) {
            if ($subType instanceof NullType) {
                return true;
            }
        }

        return false;
    }
}
