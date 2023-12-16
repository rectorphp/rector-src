<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class SkipTernary
{
    public function test($value)
    {
        return $value ? 10 : 0;
    }
}
