<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipIfElseReturn
{
    public function run(int $number)
    {
        if ($number > 50) {
            return 'yes';
        } else {
            return 100;
        }
    }
}
