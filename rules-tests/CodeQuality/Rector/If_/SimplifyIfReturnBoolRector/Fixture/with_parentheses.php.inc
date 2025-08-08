<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

final class WithParentheses
{
    public static function isLeapYear(int $year): bool
    {
        if (($year % 400) == 0 || (($year & 4) == 0 && ($year % 100) != 0)) {
            return true;
        }
        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

final class WithParentheses
{
    public static function isLeapYear(int $year): bool
    {
        return ($year % 400) == 0 || (($year & 4) == 0 && ($year % 100) != 0);
    }
}

?>
