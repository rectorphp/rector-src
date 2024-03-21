<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipReturnNoValue
{
    public function run(int $number)
    {
        if ($number > 50) {
            return;
        }

        if ($number < 50) {
            return 100;
        }
    }
}
