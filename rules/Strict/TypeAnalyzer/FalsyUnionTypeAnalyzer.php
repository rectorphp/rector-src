<?php

declare(strict_types=1);

namespace Rector\Strict\TypeAnalyzer;

use PHPStan\Type\ArrayType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;

final class FalsyUnionTypeAnalyzer
{
    public function count(UnionType $unionType): int
    {
        $falsyTypesCount = 0;

        foreach ($unionType->getTypes() as $unionedType) {
            if ($unionedType instanceof StringType) {
                ++$falsyTypesCount;
            }

            if ($unionedType instanceof IntegerType) {
                ++$falsyTypesCount;
            }

            if ($unionedType instanceof FloatType) {
                ++$falsyTypesCount;
            }

            if ($unionedType instanceof ArrayType) {
                ++$falsyTypesCount;
            }
        }

        return $falsyTypesCount;
    }
}
