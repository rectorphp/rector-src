<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToMatchRector\Fixture;

class SkipBreak
{
    public function run(int $int): string
    {
        $str = 'error';
        switch (true) {
            case $int === 0:
                $str = 'zero';
                break;
            default:
                $str = 'other';
                break;
        }

        return $str;
    }
}
