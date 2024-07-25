<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTomorrow
{
    public function run()
    {
        $date = new \DateTime('tomorrow');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeTomorrow
{
    public function run()
    {
        $date = \Carbon\Carbon::tomorrow();
    }
}

?>
