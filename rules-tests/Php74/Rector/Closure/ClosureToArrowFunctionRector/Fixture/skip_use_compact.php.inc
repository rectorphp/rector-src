<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

final class SkipUseCompact
{
    public function run(bool $param)
    {
        $test = 'Super';

        $this->myCallback(
            function () use ($test) {
                return compact('test');
            }
        );
    }

    private function myCallback($callback)
    {
        $callback();
    }
}
