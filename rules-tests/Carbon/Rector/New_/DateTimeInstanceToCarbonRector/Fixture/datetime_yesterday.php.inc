<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeYesterday
{
    public function run()
    {
        $date = new \DateTime('yesterday');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeYesterday
{
    public function run()
    {
        $date = \Carbon\Carbon::yesterday();
    }
}

?>
