<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipIntCasesOnStringCond
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
