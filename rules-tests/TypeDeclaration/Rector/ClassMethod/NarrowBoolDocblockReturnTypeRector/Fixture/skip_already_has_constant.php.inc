<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class SkipAlreadyHasConstant
{
    /**
     * @return false|bool|string[]
     */
    public function run(): false|array
    {
        return false;
    }
}
