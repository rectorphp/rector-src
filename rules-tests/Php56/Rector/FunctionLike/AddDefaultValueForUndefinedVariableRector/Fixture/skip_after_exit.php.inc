<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

final class SkipAfterExit
{
    public function run()
    {
        exit();
        $test = true;

        return $test;
    }
}
