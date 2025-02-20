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

class StrtotimeExample
{
    public function run()
    {
        $timestamp = \Carbon\Carbon::parse('2025-02-20')->timestamp;
    }
}
