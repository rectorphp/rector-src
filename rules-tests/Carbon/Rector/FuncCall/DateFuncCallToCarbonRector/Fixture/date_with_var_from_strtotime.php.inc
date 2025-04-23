<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class DateWithVarFromStrtotimeExample
{
    public function run()
    {
        $var = strtotime('2025-02-21');
        $formatted = date('d.m.Y', $var);
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class DateWithVarFromStrtotimeExample
{
    public function run()
    {
        $var = \Carbon\Carbon::parse('2025-02-21')->getTimestamp();
        $formatted = \Carbon\Carbon::createFromTimestamp($var)->format('d.m.Y');
    }
}

?>
