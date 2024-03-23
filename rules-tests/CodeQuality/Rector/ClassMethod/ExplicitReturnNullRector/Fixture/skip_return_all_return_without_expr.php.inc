<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipAllReturnWithoutExpr
{
    public function run(int $number)
    {
        if ($number > 50) {
            echo 'test';
            return;
        }

        if ($number < 50) {
            echo 'yes';
            return;
        }
    }
}
