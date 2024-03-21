<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipAlreadyReturn
{
    public function run(int $number)
    {
        if ($number > 50) {
            return 'yes';
        }

        return 100;
    }
}
