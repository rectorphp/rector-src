<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class SkipNoDocblock
{
    public function run(): false|array
    {
        return false;
    }
}
