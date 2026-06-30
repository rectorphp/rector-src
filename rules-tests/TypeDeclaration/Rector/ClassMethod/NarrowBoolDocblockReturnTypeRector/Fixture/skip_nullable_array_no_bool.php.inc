<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class SkipNullableArrayNoBool
{
    /**
     * @return mixed[]|null
     */
    private function extractConstantArrayTypeValue(array $values): ?array
    {
        return $values;
    }
}
