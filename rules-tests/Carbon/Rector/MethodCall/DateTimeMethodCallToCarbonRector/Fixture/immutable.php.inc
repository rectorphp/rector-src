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
        $date = (\Carbon\CarbonImmutable::parse('today +20 day'))->format('Y-m-d');
    }
}

?>
