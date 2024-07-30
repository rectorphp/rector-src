<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeWithDateTime
{
    public function run()
    {
        $date = new \DateTime('2024-07-30');
        $time = new \DateTime('11:12:13');
        $datetime = new \DateTime('2024-07-30 11:12:13');

        $tomorrowTime = new \DateTime('tomorrow 12:00');
        $yesterdayTime = new \DateTime('yesterday 12:00');

        $dateNoon = new \DateTime('2024-07-30 noon');
        $restStringToParse = new \DateTime('tomorrow noon');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeWithDateTime
{
    public function run()
    {
        $date = \Carbon\Carbon::now()->setDate(2024, 7, 30);
        $time = \Carbon\Carbon::now()->setTime(11, 12, 13);
        $datetime = \Carbon\Carbon::now()->setDate(2024, 7, 30)->setTime(11, 12, 13);

        $tomorrowTime = \Carbon\Carbon::tomorrow()->setTime(12, 0, 0);
        $yesterdayTime = \Carbon\Carbon::yesterday()->setTime(12, 0, 0);

        $dateNoon = \Carbon\Carbon::parse('noon')->setDate(2024, 7, 30);
        $restStringToParse = \Carbon\Carbon::parse('noon tomorrow');
    }
}

?>
