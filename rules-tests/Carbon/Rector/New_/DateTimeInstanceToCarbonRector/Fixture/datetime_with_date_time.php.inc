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

        $fullDate = new \DateTime('2024-07-30T11:12:13.000Z');
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
        $date = \Carbon\Carbon::parse('2024-07-30');
        $time = \Carbon\Carbon::parse('11:12:13');
        $datetime = \Carbon\Carbon::parse('2024-07-30 11:12:13');

        $tomorrowTime = \Carbon\Carbon::parse('12:00 tomorrow');
        $yesterdayTime = \Carbon\Carbon::parse('12:00 yesterday');

        $dateNoon = \Carbon\Carbon::parse('2024-07-30 noon');
        $restStringToParse = \Carbon\Carbon::parse('noon tomorrow');

        $fullDate = \Carbon\Carbon::parse('2024-07-30T11:12:13.000Z');
    }
}

?>
