<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

final class SkipDifferentOrder
{
    public function run()
    {
        $first = 100;
        $second = 200;

        return $second ** $first;
    }
}
