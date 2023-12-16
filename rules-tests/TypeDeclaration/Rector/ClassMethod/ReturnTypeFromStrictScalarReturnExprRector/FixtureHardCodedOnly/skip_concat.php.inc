<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class SkipConcat
{
    public function test()
    {
        return 'hello' . 'world';
    }
}
