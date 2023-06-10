<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class SkipVariableVariable
{
    public function run($abcdef = 'foo')
    {
        $$abcdef = 'test';

        return $foo;
    }
}
