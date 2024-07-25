<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeParse
{
    public function run()
    {
        $date = new \DateTime('next week');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeParse
{
    public function run()
    {
        $date = \Carbon\Carbon::parse('next week');
    }
}

?>
