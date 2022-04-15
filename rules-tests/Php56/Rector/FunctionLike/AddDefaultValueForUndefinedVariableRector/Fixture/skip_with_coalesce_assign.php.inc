<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class SkipWithCoalesceAssign
{
    public function run()
    {
        $a ??= 'test';
    }
}