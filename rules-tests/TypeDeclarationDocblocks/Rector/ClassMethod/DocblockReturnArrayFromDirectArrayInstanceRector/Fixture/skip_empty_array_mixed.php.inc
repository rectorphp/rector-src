<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SkipEmptyArrayMixed
{
    /**
     * @return mixed[]
     */
    private static function toArray(): array
    {
        return [];
    }
}
