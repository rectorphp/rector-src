<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class StrtotimeWithBaseTimestampExample
{
    public function run()
    {
        $timestamp = strtotime('+1 day', strtotime('1:00 am'));
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class StrtotimeWithBaseTimestampExample
{
    public function run()
    {
        $timestamp = strtotime('+1 day', \Carbon\Carbon::parse('1:00 am')->getTimestamp());
    }
}

?>
