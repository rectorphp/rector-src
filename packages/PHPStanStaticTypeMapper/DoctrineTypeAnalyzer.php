<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper;

use PHPStan\Type\ArrayType;
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

            if ($unionedType->isArray()->yes()) {
                $arrayType = $unionedType;
            }
        }

        if (! $hasDoctrineCollectionType) {
            return false;
        }

        return $arrayType->isArray()->yes();
    }

    private function isCollectionObjectType(Type $type): bool
    {
        if (! $type instanceof TypeWithClassName) {
            return false;
        }

        return $type->getClassName() === 'Doctrine\Common\Collections\Collection';
    }
}
