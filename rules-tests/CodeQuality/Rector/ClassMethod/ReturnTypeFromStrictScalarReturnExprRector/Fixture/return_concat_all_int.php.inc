<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class SkipReturnConcatAllInt
{
    public function run(string $value)
    {
        return $value . 'append';
    }
}
