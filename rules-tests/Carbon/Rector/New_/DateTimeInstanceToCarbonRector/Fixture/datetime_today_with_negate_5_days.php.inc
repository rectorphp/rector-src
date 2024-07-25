<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTodayWithNegative5Days
{
    public function run()
    {
        $date = new \DateTime('today - 5 day');

        $middleDate = new \DateTime('today -7day');

        $nextDate = new \DateTime('today -10 days');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTodayWithNegative5Days
{
    public function run()
    {
        $date = \Carbon\Carbon::today()->subDays(5);

        $middleDate = \Carbon\Carbon::today()->subDays(7);

        $nextDate = \Carbon\Carbon::today()->subDays(10);
    }
}

?>
