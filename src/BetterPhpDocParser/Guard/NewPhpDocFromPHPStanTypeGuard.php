<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\Guard;

use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

final class NewPhpDocFromPHPStanTypeGuard
{
    public function isLegal(Type $type): bool
    {
        if ($type instanceof UnionType) {
            return $this->isLegalUnionType($type);
        }

        return true;
    }

    private function isLegalUnionType(UnionType $type): bool
    {
        return array_all($type->getTypes(), fn (Type $unionType): bool => ! $unionType instanceof MixedType);
    }
}
