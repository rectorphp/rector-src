<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class SkipNativeBool
{
    /**
     * @return bool|string[]
     */
    public function run(): bool|array
    {
        return false;
    }
}
