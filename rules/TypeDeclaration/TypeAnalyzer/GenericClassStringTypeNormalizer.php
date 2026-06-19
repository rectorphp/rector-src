<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

final readonly class GenericClassStringTypeNormalizer
{
    public function isAllGenericClassStringType(UnionType $unionType): bool
    {
        return array_all($unionType->getTypes(), fn (Type $type): bool => $type instanceof GenericClassStringType);
    }
}
