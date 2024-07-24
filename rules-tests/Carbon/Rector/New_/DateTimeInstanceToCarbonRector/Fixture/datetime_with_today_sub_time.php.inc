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
        $addHours = \Carbon\Carbon::parse('+ 5 hours');
        $addMinutes = \Carbon\Carbon::parse('+ 5 minutes');
        $addSeconds = \Carbon\Carbon::parse('+ 5 seconds');

        $subHours = \Carbon\Carbon::parse('- 5 hours');
        $subMinuts = \Carbon\Carbon::parse('- 5 minutes');
        $subSeconds = \Carbon\Carbon::parse('- 5 seconds');
    }
}

?>
