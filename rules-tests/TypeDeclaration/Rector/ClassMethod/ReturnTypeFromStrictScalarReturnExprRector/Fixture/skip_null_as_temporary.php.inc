<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

class SkipNullAsTemporary
{
    public function getValue()
    {
        return null;
    }
}
