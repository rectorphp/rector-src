<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class SkipReturnConcatAllInt
{
    public function run()
    {
        return 1 . 1;
    }
}
