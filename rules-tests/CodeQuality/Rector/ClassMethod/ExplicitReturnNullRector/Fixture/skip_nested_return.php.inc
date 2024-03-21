<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipNestedReturn
{
    public function run(int $number)
    {
        $result = function () {
            return 100;
        };
    }
}
