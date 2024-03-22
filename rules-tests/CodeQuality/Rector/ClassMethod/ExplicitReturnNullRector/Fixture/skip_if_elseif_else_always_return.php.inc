<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipIfElseReturnInTryCatch
{
    public function run(int $number)
    {
        if (rand(0, 1)) {
            return 1;
         } elseif (rand(0, 2)) {
            return 3;
         } else {
             return 2;
         }
    }
}
