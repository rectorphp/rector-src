<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class SkipNoBoolInDocblock
{
    /**
     * @return false|string[]
     */
    public function run(): false|array
    {
        return false;
    }
}
