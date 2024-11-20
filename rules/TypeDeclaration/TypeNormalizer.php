<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration;

use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use PHPStan\Type\UnionType;
use Rector\TypeDeclaration\ValueObject\NestedArrayType;
use Rector\Util\Reflection\PrivatesAccessor;

/**
 * @see \Rector\Tests\TypeDeclaration\TypeNormalizerTest
 */
final class TypeNormalizer
{
    /**
     * @var NestedArrayType[]
     */
    private array $collectedNestedArrayTypes = [];

    public function __construct(
        private readonly PrivatesAccessor $privatesAccessor
    ) {
    }

    /**
     * @api
     *
     * Turn nested array union types to unique ones:
     * e.g. int[]|string[][]|bool[][]|string[][]
     * ↓
     * int[]|string[][]|bool[][]
     */
    public function normalizeArrayOfUnionToUnionArray(Type $type, int $arrayNesting = 1): Type
    {
        if (! $type->isArray()->yes()) {
            return $type;
        }

        if ($type instanceof UnionType || $type instanceof IntersectionType) {
            return $type;
        }

        /** @var ArrayType|ConstantArrayType $type */
        if ($type instanceof ConstantArrayType && $arrayNesting === 1) {
            return $type;
        }

        // first collection of types
        if ($arrayNesting === 1) {
            $this->collectedNestedArrayTypes = [];
        }

        if ($type->getItemType() instanceof ArrayType) {
            ++$arrayNesting;
            $this->normalizeArrayOfUnionToUnionArray($type->getItemType(), $arrayNesting);
        } elseif ($type->getItemType() instanceof UnionType) {
            $this->collectNestedArrayTypeFromUnionType($type->getItemType(), $arrayNesting);
        } else {
            $this->collectedNestedArrayTypes[] = new NestedArrayType(
                $type->getItemType(),
                $arrayNesting,
                $type->getKeyType()
            );
        }

        return $this->createUnionedTypesFromArrayTypes($this->collectedNestedArrayTypes);
    }

    /**
     * From "string[]|mixed[]" based on empty array to to "string[]"
     */
    public function normalizeArrayTypeAndArrayNever(Type $type): Type
    {
        return TypeTraverser::map($type, function (Type $traversedType, callable $traverserCallable): Type {
            if ($this->isConstantArrayNever($traversedType)) {
                assert($traversedType instanceof ConstantArrayType);

                // not sure why, but with direct new node everything gets nulled to MixedType
                $this->privatesAccessor->setPrivateProperty($traversedType, 'keyTypes', [new MixedType()]);

                $this->privatesAccessor->setPrivateProperty($traversedType, 'valueTypes', [new MixedType()]);

                return $traversedType;
            }

            if ($traversedType instanceof NeverType) {
                return new MixedType();
            }

            return $traverserCallable($traversedType, $traverserCallable);
        });
    }

    private function isConstantArrayNever(Type $type): bool
    {
        return $type instanceof ConstantArrayType && $type->getKeyType() instanceof NeverType && $type->getItemType() instanceof NeverType;
    }

    private function collectNestedArrayTypeFromUnionType(UnionType $unionType, int $arrayNesting): void
    {
        foreach ($unionType->getTypes() as $unionedType) {
            if ($unionedType->isArray()->yes()) {
                ++$arrayNesting;
                $this->normalizeArrayOfUnionToUnionArray($unionedType, $arrayNesting);
            } else {
                $this->collectedNestedArrayTypes[] = new NestedArrayType($unionedType, $arrayNesting);
            }
        }
    }

    /**
     * @param NestedArrayType[] $collectedNestedArrayTypes
     */
    private function createUnionedTypesFromArrayTypes(array $collectedNestedArrayTypes): UnionType | ArrayType
    {
        $unionedTypes = [];
        foreach ($collectedNestedArrayTypes as $collectedNestedArrayType) {
            $arrayType = $collectedNestedArrayType->getType();
            for ($i = 0; $i < $collectedNestedArrayType->getArrayNestingLevel(); ++$i) {
                $arrayType = new ArrayType($collectedNestedArrayType->getKeyType(), $arrayType);
            }

            /** @var ArrayType $arrayType */
            $unionedTypes[] = $arrayType;
        }

        if (count($unionedTypes) > 1) {
            return new UnionType($unionedTypes);
        }

        return $unionedTypes[0];
    }
}
