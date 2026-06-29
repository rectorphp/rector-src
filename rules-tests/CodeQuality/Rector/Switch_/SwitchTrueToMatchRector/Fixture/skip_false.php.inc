<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToMatchRector\Fixture;

class SkipFalse
{
    public function run($value)
    {
        switch (false) {
            case $value === 0:
                return 'no';
            default:
                return 'maybe';
        }
    }
}
