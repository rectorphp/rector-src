<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class TimeCalculationExample
{
    public function run()
    {
        $twoSecondsAgo = time() - 2;
        $twoMinutesAgo = time() - (60 * 2);
        $twoHoursAgo   = time() - (60 * 60 * 2);
        $twoDaysAgo    = time() - (60 * 60 * 24 * 2);
        $twoWeeksAgo   = time() - (60 * 60 * 24 * 14);
    }
}

-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class TimeCalculationExample
{
    public function run()
    {
        $twoSecondsAgo = \Carbon\Carbon::now()->subSeconds(2)->getTimestamp();
        $twoMinutesAgo = \Carbon\Carbon::now()->subMinutes(2)->getTimestamp();
        $twoHoursAgo   = \Carbon\Carbon::now()->subHours(2)->getTimestamp();
        $twoDaysAgo    = \Carbon\Carbon::now()->subDays(2)->getTimestamp();
        $twoWeeksAgo   = \Carbon\Carbon::now()->subWeeks(2)->getTimestamp();
    }
}
