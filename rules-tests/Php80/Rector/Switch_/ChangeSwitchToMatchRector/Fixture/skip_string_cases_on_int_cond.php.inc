<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipStringCasesOnIntCond
{
    function test(int $code): string
    {
        switch($code) {
            case '777':
                return '1';
            case '88':
                return '2';
            default:
                return '4';
        };
    }
}

?>
