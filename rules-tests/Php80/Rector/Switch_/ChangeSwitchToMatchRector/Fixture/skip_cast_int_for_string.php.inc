<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

/**
 * switch compares loosely, "777abc" == 777 is false,
 * but match ((int) "777abc") would hit the 777 arm
 */
final class SkipCastIntForString
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
