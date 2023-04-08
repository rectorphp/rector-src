<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\TypeComparator;

use PHPStan\Type\ClassStringType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;

/**
 * @see \Rector\Tests\NodeTypeResolver\TypeComparator\ScalarTypeComparatorTest
 */
final class ScalarTypeComparator
{
    public function areEqualScalar(Type $firstType, Type $secondType): bool
    {
        if ($firstType instanceof StringType && $secondType instanceof StringType) {
            // prevents "class-string" vs "string"
            $firstTypeClass = $firstType::class;
            $secondTypeClass = $secondType::class;

            return $firstTypeClass === $secondTypeClass;
        }

        if ($firstType instanceof IntegerType && $secondType instanceof IntegerType) {
            return true;
        }

        if ($firstType instanceof FloatType && $secondType instanceof FloatType) {
            return true;
        }

        if (! $firstType->isBoolean()->yes()) {
            return false;
        }

        return $secondType->isBoolean()
            ->yes();
    }

    /**
     * E.g. first is string, second is bool
     */
    public function areDifferentScalarTypes(Type $firstType, Type $secondType): bool
    {
        if (! $this->isScalarType($firstType)) {
            return false;
        }

        if (! $this->isScalarType($secondType)) {
            return false;
        }

        // treat class-string and string the same
        if ($firstType instanceof ClassStringType && $secondType instanceof StringType) {
            return false;
        }

        if (! $firstType instanceof StringType) {
            return $firstType::class !== $secondType::class;
        }

        if (! $secondType instanceof ClassStringType) {
            return $firstType::class !== $secondType::class;
        }

        return false;
    }

    private function isScalarType(Type $type): bool
    {
        if ($type instanceof StringType) {
            return true;
        }

        if ($type instanceof FloatType) {
            return true;
        }

        if ($type instanceof IntegerType) {
            return true;
        }

        return $type->isBoolean()
            ->yes();
    }
}
