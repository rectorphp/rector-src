<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipCastStringForIntWithNumericString
{
    public function run(int $code): string
    {
        switch ($code) {
            case '01':
                return 'one';
            case '2':
                return 'two';
            default:
                return 'other';
        }
    }
}
