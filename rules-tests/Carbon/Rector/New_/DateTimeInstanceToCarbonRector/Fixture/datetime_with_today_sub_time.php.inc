<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTodayAddSubTime
{
    public function run()
    {
        $addHours = new \DateTime('+ 5 hours');
        $addMinutes = new \DateTime('+ 5 minutes');
        $addSeconds = new \DateTime('+ 5 seconds');

        $subHours = new \DateTime('- 5 hours');
        $subMinuts = new \DateTime('- 5 minutes');
        $subSeconds = new \DateTime('- 5 seconds');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTodayAddSubTime
{
    public function run()
    {
        $addHours = \Carbon\Carbon::now()->addHours(5);
        $addMinutes = \Carbon\Carbon::now()->addMinutes(5);
        $addSeconds = \Carbon\Carbon::now()->addSeconds(5);

        $subHours = \Carbon\Carbon::now()->subHours(5);
        $subMinuts = \Carbon\Carbon::now()->subMinutes(5);
        $subSeconds = \Carbon\Carbon::now()->subSeconds(5);
    }
}

?>
