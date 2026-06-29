<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToMatchRector\Fixture;

class SkipDefaultNotLast
{
    public function run($value)
    {
        switch (true) {
            default:
                return 'maybe';
            case $value === 0:
                return 'no';
        }
    }
}
