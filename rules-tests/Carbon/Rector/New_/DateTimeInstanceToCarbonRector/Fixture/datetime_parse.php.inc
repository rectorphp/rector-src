<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class DateTimeParse
{
    public function run()
    {
        $date = new \DateTime('next week');
        $exactDate = new \DateTime('2024-07-25');
        $textualDate = new \DateTime('2 days ago');
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
        $exactDate = \Carbon\Carbon::now()->setDate(2024, 7, 25);
        $textualDate = \Carbon\Carbon::parse('2 days ago');
    }
}

?>
