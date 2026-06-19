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
<<<<<<< HEAD
        return array_all($type->getTypes(), fn (Type $unionType): bool => ! $unionType instanceof MixedType);
=======
        return array_all($type->getTypes(), fn ($unionType): bool => ! $unionType instanceof MixedType);
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
    }
}
