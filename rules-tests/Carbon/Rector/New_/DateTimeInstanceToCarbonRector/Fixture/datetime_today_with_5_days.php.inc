<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTodayWith5Days
{
    public function run()
    {
        $date = new \DateTime('today + 5 day');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTodayWith5Days
{
    public function run()
    {
        $date = \Carbon\Carbon::today()->addDays(5);
    }
}

?>
