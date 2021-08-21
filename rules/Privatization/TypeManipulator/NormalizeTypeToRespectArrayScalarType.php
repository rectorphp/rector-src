<?php

declare(strict_types=1);

namespace Rector\Privatization\TypeManipulator;

use PhpParser\Node;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\Accessory\NonEmptyArrayType;
use Rector\NodeNameResolver\NodeNameResolver;

final class NormalizeTypeToRespectArrayScalarType
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function normalizeToArray(Type $type, ?Node $returnNode): Type
    {
        if ($returnNode === null) {
            return $type;
        }

        if (! $this->nodeNameResolver->isName($returnNode, 'array')) {
            return $type;
        }

        if ($type instanceof UnionType) {
            return $this->normalizeUnionType($type);
        }

        if ($type instanceof MixedType) {
            return new ArrayType($type, $type);
        }

        if ($type instanceof ArrayType) {
            $itemType = $type->getItemType();
            if (! $itemType instanceof IntersectionType) {
                return $type;
            }

            $types = $itemType->getTypes();
            foreach ($types as $key => $itemTypeType) {
                if ($itemTypeType instanceof NonEmptyArrayType) {
                    unset($types[$key]);
                }
            }

            $type = new ArrayType($type->getKeyType(), new IntersectionType($types));
        }

        return $type;
    }

    private function normalizeUnionType(UnionType $unionType): UnionType
    {
        $normalizedTypes = [];
        foreach ($unionType->getTypes() as $unionedType) {
            if ($unionedType instanceof MixedType) {
                $normalizedTypes[] = new ArrayType($unionedType, $unionedType);
                continue;
            }

            $normalizedTypes[] = $unionedType;
        }

        if ($unionType->getTypes() === $normalizedTypes) {
            return $unionType;
        }

        return new UnionType($normalizedTypes);
    }
}
