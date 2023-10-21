<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

class SkipFirstClassCallable
{
    public function run()
    {
        array_merge(...);
    }
}
