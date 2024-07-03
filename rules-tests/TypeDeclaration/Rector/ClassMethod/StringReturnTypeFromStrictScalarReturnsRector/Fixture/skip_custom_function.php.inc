<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

function selfsubstr(): string
{
    return 'hi';
}

final class SkipEmptyReturn
{
    public function run($value)
    {
        if ($value) {
            return true;
        }

        return selfsubstr('warning', 1);
    }
}
