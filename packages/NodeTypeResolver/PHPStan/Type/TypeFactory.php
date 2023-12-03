<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Type;

use Rector\NodeTypeResolver\PHPStan\ObjectWithoutClassTypeWithParentTypes;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantFloatType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeUtils;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\PHPStan\TypeHasher;

final class TypeFactory
{
    public function __construct(
        private readonly TypeHasher $typeHasher,
    ) {
    }

    /**
     * @param Type[] $types
     */
    public function createMixedPassedOrUnionTypeAndKeepConstant(array $types): Type
    {
        $types = $this->unwrapUnionedTypes($types);
        $types = $this->uniquateTypes($types, true);

        return $this->createUnionOrSingleType($types);
    }

    /**
     * @param Type[] $types
     */
    public function createMixedPassedOrUnionType(array $types, bool $keepConstantTypes = false): Type
    {
        $types = $this->unwrapUnionedTypes($types);
        $types = $this->uniquateTypes($types, $keepConstantTypes);

        return $this->createUnionOrSingleType($types);
    }

    /**
     * @template TType as Type
     * @param array<TType> $types
     * @return array<TType>
     */
    public function uniquateTypes(array $types, bool $keepConstant = false): array
    {
        $constantTypeHashes = [];
        $uniqueTypes = [];
        $totalTypes = count($types);

        foreach ($types as $type) {
            if ($totalTypes > 1 && $type instanceof ObjectWithoutClassTypeWithParentTypes) {
                $parents = $type->getParentTypes();
                $type = new ObjectType($parents[0]->getClassName());
            }

            $removedConstantType = $this->removeValueFromConstantType($type);
            $removedConstantTypeHash = $this->typeHasher->createTypeHash($removedConstantType);

            if ($keepConstant && $type !== $removedConstantType) {
                $typeHash = $this->typeHasher->createTypeHash($type);
                $constantTypeHashes[$typeHash] = $removedConstantTypeHash;
            } else {
                $type = $removedConstantType;
                $typeHash = $removedConstantTypeHash;
            }

            $uniqueTypes[$typeHash] = $type;
        }

        foreach ($constantTypeHashes as $constantTypeHash => $removedConstantTypeHash) {
            if (array_key_exists($removedConstantTypeHash, $uniqueTypes)) {
                unset($uniqueTypes[$constantTypeHash]);
            }
        }

        // re-index
        return array_values($uniqueTypes);
    }

    /**
     * @param Type[] $types
     * @return Type[]
     */
    private function unwrapUnionedTypes(array $types): array
    {
        // unwrap union types
        $unwrappedTypes = [];
        foreach ($types as $type) {
            $flattenTypes = TypeUtils::flattenTypes($type);

            foreach ($flattenTypes as $flattenType) {
                if ($flattenType instanceof ConstantArrayType) {
                    $unwrappedTypes = [...$unwrappedTypes, ...$this->unwrapConstantArrayTypes($flattenType)];
                } else {
                    $unwrappedTypes = $this->resolveNonConstantArrayType($flattenType, $unwrappedTypes);
                }
            }
        }

        return $unwrappedTypes;
    }

    /**
     * @param Type[] $unwrappedTypes
     * @return Type[]
     */
    private function resolveNonConstantArrayType(Type $type, array $unwrappedTypes): array
    {
        $unwrappedTypes[] = $type;
        return $unwrappedTypes;
    }

    /**
     * @param Type[] $types
     */
    private function createUnionOrSingleType(array $types): Type
    {
        if ($types === []) {
            return new MixedType();
        }

        if (count($types) === 1) {
            return $types[0];
        }

        return new UnionType($types);
    }

    private function removeValueFromConstantType(Type $type): Type
    {
        // remove values from constant types
        if ($type instanceof ConstantFloatType) {
            return new FloatType();
        }

        if ($type instanceof ConstantStringType) {
            return new StringType();
        }

        if ($type instanceof ConstantIntegerType) {
            return new IntegerType();
        }

        if ($type instanceof ConstantBooleanType) {
            return new BooleanType();
        }

        return $type;
    }

    /**
     * @return Type[]
     */
    private function unwrapConstantArrayTypes(ConstantArrayType $constantArrayType): array
    {
        $unwrappedTypes = [];

        $flattenKeyTypes = TypeUtils::flattenTypes($constantArrayType->getKeyType());
        $flattenItemTypes = TypeUtils::flattenTypes($constantArrayType->getItemType());

        foreach ($flattenItemTypes as $position => $nestedFlattenItemType) {
            $nestedFlattenKeyType = $flattenKeyTypes[$position] ?? null;
            if (! $nestedFlattenKeyType instanceof Type) {
                $nestedFlattenKeyType = new MixedType();
            }

            $unwrappedTypes[] = new ArrayType($nestedFlattenKeyType, $nestedFlattenItemType);
        }

        return $unwrappedTypes;
    }
}
