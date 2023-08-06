<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class SkipMultiReturn
{
    public function run($value)
    {
        if ($value) {
            return false;
        }

        return substr('warning', 1);
    }
}
