<?php

namespace Rector\Tests\Carbon\Rector\MethodCall\DateTimeMethodCallToCarbonRector\Fixture;

final class Immutable
{
    public function run()
    {
        $date = (new \DateTimeImmutable('today +20 day'))->format('Y-m-d');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\MethodCall\DateTimeMethodCallToCarbonRector\Fixture;

final class Immutable
{
    public function run()
    {
        $date = (\Carbon\CarbonImmutable::today()->addDays(20))->format('Y-m-d');
    }
}

?>
