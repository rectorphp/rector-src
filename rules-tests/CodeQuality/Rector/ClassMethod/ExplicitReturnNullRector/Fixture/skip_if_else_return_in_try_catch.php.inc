<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipIfElseReturnInTryCatch
{
    public function run(int $number)
    {
        if (rand(0, 1)) {
            try {
                return execute();
            } catch (\Exception $e) {
                return 2;
            }
         } else {
             return 2;
         }
    }
}
