<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

final class SkipCompareAssignOp
{
    public function run($first, $second)
    {
        $first += 1 + 1 + 1 + 1 +1 + 1 + 1 + 1 + 1 + 1;
        $second += 2 + 2 + 2 + 2 + 2 + 2 + 2 + 2 + 2 + 2;

        return $first <=> $second;
    }
}
