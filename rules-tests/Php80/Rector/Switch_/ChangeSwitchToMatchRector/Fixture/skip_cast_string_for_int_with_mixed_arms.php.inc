<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipCastStringForIntWithMixedArms
{
    /**
     * @param string $other
     */
    public function run(int $code, $other): string
    {
        switch ($code) {
            case '2':
                return 'two';
            case $other:
                return 'other';
            default:
                return 'default';
        }
    }
}
