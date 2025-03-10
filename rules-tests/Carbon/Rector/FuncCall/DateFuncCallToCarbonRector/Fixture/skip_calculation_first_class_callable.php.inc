<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class TimeCalculationExample
{
    public function run()
    {
        $twoSecondsAgo = time(...) - 2;
    }
}
