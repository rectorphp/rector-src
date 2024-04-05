<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipNeverReturn
{
    public function run(bool $param)
    {
        if (rand(0,1)) {
            return 5;
        }

        $this->neverReturns();
    }

    private function neverReturns($value): never
    {
        exit();
    }
}
?>
