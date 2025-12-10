<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipNullVariableCall
{
    public function run()
    {
        $bar = null;

        fn ($foo) => $bar->foo($foo);

        function ($foo) use ($bar)
        {
            return $bar->foo($foo);
        };
    }
}
