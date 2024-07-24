<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTodayWith5Days
{
    public function run()
    {
        $date = new \DateTime('today + 5 day');

        $middleDate = new \DateTime('today +7day');

        $nextDate = new \DateTime('today +10 days');
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
        $date = \Carbon\Carbon::parse('today + 5 day');

        $middleDate = \Carbon\Carbon::parse('today +7day');

        $nextDate = \Carbon\Carbon::parse('today +10 days');
    }
}

?>
