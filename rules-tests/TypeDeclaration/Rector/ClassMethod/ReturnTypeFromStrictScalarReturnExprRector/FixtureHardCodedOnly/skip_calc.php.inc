<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class SkipCalc
{
    public function test()
    {
        return 1 + 2;
    }
}
