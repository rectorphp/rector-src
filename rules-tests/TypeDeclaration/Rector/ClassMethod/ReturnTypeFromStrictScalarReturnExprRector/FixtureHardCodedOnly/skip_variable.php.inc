<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class SkipVariable
{
    public function test()
    {
        $value = 'test';
        return $value;
    }
}
