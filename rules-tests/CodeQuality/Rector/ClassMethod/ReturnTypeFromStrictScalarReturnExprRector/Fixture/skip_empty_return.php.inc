<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class SkipEmptyReturn
{
    public function run($value)
    {
        if ($value) {
            return;
        }

        return substr('warning', 1);
    }
}
