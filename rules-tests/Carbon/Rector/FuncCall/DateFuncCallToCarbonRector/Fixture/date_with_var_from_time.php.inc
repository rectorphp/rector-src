<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class DateWithVarFromTimeExample
{
    public function run()
    {
        $var = time();
        $formatted = date('d.m.Y', $var);
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class DateWithVarFromTimeExample
{
    public function run()
    {
        $var = time();
        $formatted = \Carbon\Carbon::createFromTimestamp($var)->format('d.m.Y');
    }
}

?>
