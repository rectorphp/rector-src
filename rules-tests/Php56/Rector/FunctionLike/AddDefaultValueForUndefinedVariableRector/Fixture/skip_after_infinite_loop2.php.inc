<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

final class SkipAfterInfiniteLoop2
{
    public function run(Item $yes)
    {
        echo 'test';

        while (true !== false) {
        }

        $a = [];

        if ($b = $yes) {
            $a[] = 'test';
        }

        return $a;
    }
}
