<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class SkipFirstClassCallable
{
    public function run()
    {
        $date = date(...);
    }
}
