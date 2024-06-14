<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector\Fixture;

class SkipFirstClassCallable
{
    public function run()
    {
        some_function(...);
    }
}