<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class DateWithStrtotimeExample
{
    public function run()
    {
        $formatted = date('d.m.Y', strtotime('2025-02-21'));
    }
}

-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class DateWithStrtotimeExample
{
    public function run()
    {
        $formatted = \Carbon\Carbon::parse('2025-02-21')->format('d.m.Y');
    }
}
