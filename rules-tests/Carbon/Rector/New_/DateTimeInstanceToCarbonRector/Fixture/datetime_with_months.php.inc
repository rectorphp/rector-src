<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeNow
{
    public function run()
    {
        $date = new \DateTime('now');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeNow
{
    public function run()
    {
        $date = \Carbon\Carbon::now();
    }
}

?>
