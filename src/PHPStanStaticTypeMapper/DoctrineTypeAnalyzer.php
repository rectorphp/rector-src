<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper;

use PHPStan\Type\ArrayType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;

final class DoctrineTypeAnalyzer
{
    public function isDoctrineCollectionWithIterableUnionType(Type $type): bool
    {
        if (! $type instanceof UnionType) {
            return false;
        }

        $arrayType = null;
        $hasDoctrineCollectionType = false;
        foreach ($type->getTypes() as $unionedType) {
            if ($this->isCollectionObjectType($unionedType)) {
                $hasDoctrineCollectionType = true;
            }

            if ($unionedType instanceof ArrayType) {
                $arrayType = $unionedType;
            }
        }

        if (! $hasDoctrineCollectionType) {
            return false;
        }

        return $arrayType instanceof ArrayType;
    }

    public function isInstanceOfCollectionType(Type $type): bool
    {
        if (! $type instanceof ObjectType) {
            return false;
        }

        return $type->isInstanceOf('Doctrine\Common\Collections\Collection')
            ->yes();
    }

    private function isCollectionObjectType(Type $type): bool
    {
        if (! $type instanceof TypeWithClassName) {
            return false;
        }

        return $type->getClassName() === 'Doctrine\Common\Collections\Collection';
    }
}
