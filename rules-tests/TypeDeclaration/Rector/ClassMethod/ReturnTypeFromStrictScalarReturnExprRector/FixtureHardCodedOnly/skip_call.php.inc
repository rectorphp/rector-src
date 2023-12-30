<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class SkipCall
{
    public function test($value)
    {
        return strlen($value);
    }
}
