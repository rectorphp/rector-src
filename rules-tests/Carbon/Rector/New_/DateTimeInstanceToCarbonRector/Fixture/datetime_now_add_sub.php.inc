<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeNowAddSubTime
{
    public function run()
    {
        $addSeconds = new \DateTime('+5 seconds');
        $addMinutes = new \DateTime('+5 minutes');
        $addHours = new \DateTime('+5 hours');
        $addDays = new \DateTime('+5 days');
        $addWeeks = new \DateTime('+5 weeks');
        $addMonths = new \DateTime('+5 months');
        $addYears = new \DateTime('+5 years');

        $subSeconds = new \DateTime('-5 seconds');
        $subMinuts = new \DateTime('-5 minutes');
        $subHours = new \DateTime('-5 hours');
        $subDays = new \DateTime('-5 days');
        $subWeeks = new \DateTime('-5 weeks');
        $subMonths = new \DateTime('-5 months');
        $subYears = new \DateTime('-5 years');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeNowAddSub
{
    public function run()
    {
        $addSeconds = \Carbon\Carbon::now()->addSeconds(5);
        $addMinutes = \Carbon\Carbon::now()->addMinutes(5);
        $addHours = \Carbon\Carbon::now()->addHours(5);
        $addDays = \Carbon\Carbon::now()->addDays(5);
        $addWeeks = \Carbon\Carbon::now()->addWeeks(5);
        $addMonths = \Carbon\Carbon::now()->addMonths(5);
        $addYears = \Carbon\Carbon::now()->addYears(5);

        $subSeconds = \Carbon\Carbon::now()->subSeconds(5);
        $subMinuts = \Carbon\Carbon::now()->subMinutes(5);
        $subHours = \Carbon\Carbon::now()->subHours(5);
        $subDays = \Carbon\Carbon::now()->subDays(5);
        $subWeeks = \Carbon\Carbon::now()->subWeeks(5);
        $subMonths = \Carbon\Carbon::now()->subMonths(5);
        $subYears = \Carbon\Carbon::now()->subYears(5);
    }
}

?>
