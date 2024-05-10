<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeToday
{
    public function run()
    {
        $date = new \DateTime('today');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeToday
{
    public function run()
    {
        $date = \Carbon\Carbon::today();
    }
}

?>
