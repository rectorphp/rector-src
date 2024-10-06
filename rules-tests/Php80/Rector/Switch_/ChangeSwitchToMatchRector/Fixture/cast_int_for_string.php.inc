<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class CastIntForString
{
    function test(string $code): int
    {
        switch($code) {
            case 777:
                return 1;
            case 88:
                return 2;
            default:
                return 4;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class CastIntForString
{
    function test(string $code): int
    {
        return match ((int) $code) {
            777 => 1,
            88 => 2,
            default => 4,
        };;
    }
}

?>
