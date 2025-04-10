<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class StrtotimeWithBaseTimestampAsVarExample
{
    public function run()
    {
        $var = strtotime('1:00 am');
        $timestamp = strtotime('+1 day', $var);
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class StrtotimeWithBaseTimestampAsVarExample
{
    public function run()
    {
        $var = \Carbon\Carbon::parse('1:00 am')->getTimestamp();
        $timestamp = strtotime('+1 day', $var);
    }
}

?>
