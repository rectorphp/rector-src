<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class SkipNativeFalseAndTrue
{
    /**
     * @return bool|string[]
     */
    public function run(): false|true|array
    {
        return false;
    }
}
