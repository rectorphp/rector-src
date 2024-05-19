<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class IncludeDateTimeImmutable
{
    public function run()
    {
        $date = new \DateTimeImmutable('now');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

final class IncludeDateTimeImmutable
{
    public function run()
    {
        $date = \Carbon\CarbonImmutable::now();
    }
}

?>
