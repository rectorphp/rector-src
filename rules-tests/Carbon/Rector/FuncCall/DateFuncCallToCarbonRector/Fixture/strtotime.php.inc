<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class StrtotimeExample
{
    public function run()
    {
        $timestamp = strtotime('2025-02-20');
    }
}

-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

use Carbon\Carbon;

class StrtotimeExample
{
    public function run()
    {
        $timestamp = Carbon::parse('2025-02-20')->timestamp;
    }
}
