<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeWithMonths
{
    public function run()
    {
        $date = new \DateTime('+ 2 months');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeWithMonths
{
    public function run()
    {
        $date = \Carbon\Carbon::parse('+ 2 months');
    }
}

?>
