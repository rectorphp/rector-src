<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Helper;

use PHPStan\Type\ClosureType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

final class PhpDocNullableTypeHelper
{
    /**
     * @return Type|null Returns null if it was not possible to resolve new php doc type or if update is not required
     */
    public function resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserType(
        Type $phpDocType,
        Type $phpParserType
    ): ?Type {
        if ($phpParserType instanceof MixedType) {
            return null;
        }

        return $this->resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserTypeNullInfo(
            $phpDocType,
            $this->isParserTypeContainingNullType($phpParserType)
        );
    }

    /**
     * @param array<Type> $updatedDocTypes
     *
     * @return array<Type>
     */
    private function appendOrPrependNullTypeIfAppropriate(
        bool $isPhpParserTypeContainingNullType,
        bool $isPhpDocTypeContainingClosureType,
        array $updatedDocTypes
    ): array {
        if (! $isPhpParserTypeContainingNullType) {
            return $updatedDocTypes;
        }

        if ($isPhpDocTypeContainingClosureType) {
            array_unshift($updatedDocTypes, new NullType());
        } else {
            $updatedDocTypes[] = new NullType();
        }

        return $updatedDocTypes;
    }

    private function hasClosureType(Type $phpDocType): bool
    {
        if ($phpDocType instanceof ClosureType) {
            return true;
        }

        if ($phpDocType instanceof UnionType) {
            foreach ($phpDocType->getTypes() as $subType) {
                if ($subType instanceof ClosureType) {
                    return true;
                }
            }
        }

        return false;
    }

    private function hasNullType(Type $phpDocType): bool
    {
        if ($phpDocType instanceof UnionType) {
            return TypeCombinator::containsNull($phpDocType);
        }

        return false;
    }

    /**
     * @return Type[]
     */
    private function resolveUpdatedDocTypes(Type $phpDocType): array
    {
        $updatedDocTypes = [];
        if ($phpDocType instanceof UnionType) {
            foreach ($phpDocType->getTypes() as $subType) {
                if ($subType instanceof NullType) {
                    continue;
                }

                $updatedDocTypes[] = $subType;
            }
        } else {
            $updatedDocTypes[] = $phpDocType;
        }

        return $updatedDocTypes;
    }

    private function isItRequiredToRemoveOrAddNullTypeToUnion(
        bool $phpDocTypeContainsNullType,
        bool $phpParserTypeContainsNullType,
    ): bool {
        return ($phpParserTypeContainsNullType && ! $phpDocTypeContainsNullType) || (! $phpParserTypeContainsNullType && $phpDocTypeContainsNullType);
    }

    /**
     * @param Type[] $updatedDocTypes
     */
    private function composeUpdatedPhpDocType(array $updatedDocTypes): Type
    {
        return count($updatedDocTypes) === 1
            ? $updatedDocTypes[0]
            : new UnionType($updatedDocTypes);
    }

    private function isParserTypeContainingNullType(Type $phpParserType): bool
    {
        if ($phpParserType instanceof UnionType) {
            return TypeCombinator::containsNull($phpParserType);
        }

        return false;
    }

    private function resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserTypeNullInfo(
        Type $phpDocType,
        bool $isPhpParserTypeContainingNullType
    ): ?Type {
        $isPhpDocTypeContainingNullType = $this->hasNullType($phpDocType);
        $isPhpDocTypeContainingClosureType = $this->hasClosureType($phpDocType);
        $updatedDocTypes = $this->resolveUpdatedDocTypes($phpDocType);

        if (! $this->isItRequiredToRemoveOrAddNullTypeToUnion(
            $isPhpDocTypeContainingNullType,
            $isPhpParserTypeContainingNullType
        )) {
            return null;
        }

        $updatedDocTypes = $this->appendOrPrependNullTypeIfAppropriate(
            $isPhpParserTypeContainingNullType,
            $isPhpDocTypeContainingClosureType,
            $updatedDocTypes
        );

        return $this->composeUpdatedPhpDocType($updatedDocTypes);
    }
}
