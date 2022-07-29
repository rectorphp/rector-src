<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class SkipAlreadyInitializedNext
{
    public function run()
    {
        $a = null;
        $b = null;

        if (rand(0, 1)) {
            $a = 5;
            $b = 2;
        } else if (rand(0,1)) {
            unset($a);
            unset($b);
        }

        echo $a;
        echo $b;
    }
}
