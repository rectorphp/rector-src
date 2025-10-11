<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

final class SkipCompareBinaryOp
{
    public function run()
    {
        $first = 1 + 1 + 1 + 1 +1 + 1 + 1 + 1 + 1 + 1;
        $second = 2 + 2 + 2 + 2 + 2 + 2 + 2 + 2 + 2 + 2;

        return $first <=> $second;
    }
}
