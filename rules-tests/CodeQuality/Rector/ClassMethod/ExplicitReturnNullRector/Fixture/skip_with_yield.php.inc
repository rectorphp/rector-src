<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipWithYield
{
    public function run(int $number)
    {
        if ($number > 50) {
            return 1;
        }

        yield 'foo';
    }
}
