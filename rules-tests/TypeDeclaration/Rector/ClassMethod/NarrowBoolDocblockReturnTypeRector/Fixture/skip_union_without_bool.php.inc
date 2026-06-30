<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class SkipUnionWithoutBool
{
    /**
     * @return string|array<mixed>|int
     */
    private function parseValue(string $value): string|array|int
    {
        return $value;
    }
}
