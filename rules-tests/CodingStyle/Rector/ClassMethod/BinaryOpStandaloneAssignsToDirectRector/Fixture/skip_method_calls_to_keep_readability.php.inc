<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

final class SkipMethodCallsToKeepReadability
{
    public function run()
    {
        $first = $this->createLongValue(100);
        $second = $this->createLongValue(200);

        return $first <=> $second;
    }

    private function createLongValue($value)
    {
        return $value . ' is long';
    }
}
