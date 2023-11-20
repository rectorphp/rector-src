<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

class SkipFirstClassCallable
{
    public function run()
    {
        array_merge(...);
    }
}
