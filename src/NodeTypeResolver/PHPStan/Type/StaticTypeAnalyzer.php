<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Type;

use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\ConstantScalarType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

final readonly class StaticTypeAnalyzer
{
    public function isAlwaysTruableType(Type $type): bool
    {
        if ($type instanceof MixedType) {
            return false;
        }

        if ($type instanceof ConstantArrayType) {
            return true;
        }

        if ($type instanceof ArrayType) {
            return $this->isAlwaysTruableArrayType($type);
        }

        if ($type instanceof UnionType && TypeCombinator::containsNull($type)) {
            return false;
        }

        // always trueish
        if ($type instanceof ObjectType) {
            return true;
        }

        if ($type instanceof ConstantScalarType && ! $type->isNull()->yes()) {
            return (bool) $type->getValue();
        }

        if ($type->isScalar()->yes()) {
            return false;
        }

        return $this->isAlwaysTruableUnionType($type);
    }

    private function isAlwaysTruableUnionType(Type $type): bool
    {
        if (! $type instanceof UnionType) {
            return false;
        }
<<<<<<< HEAD

=======
>>>>>>> 424f600506 ([php] bump to PHP 8.4 syntax)
        return array_all($type->getTypes(), fn (Type $unionedType): bool => $this->isAlwaysTruableType($unionedType));
    }

    private function isAlwaysTruableArrayType(ArrayType $arrayType): bool
    {
        $itemType = $arrayType->getIterableValueType();
        if (! $itemType instanceof ConstantScalarType) {
            return false;
        }

        return (bool) $itemType->getValue();
    }
}
