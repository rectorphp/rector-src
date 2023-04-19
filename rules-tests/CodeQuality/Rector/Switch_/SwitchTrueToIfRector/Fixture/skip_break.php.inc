<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SwitchTrueToIfRector\Fixture;

final class SkipBreak
{
    public function run(int $int): string
    {
        $str = 'error';
        switch (true) {
            case $int === 0:
                $str = 'zero';
                break;
            case $int%2 === 1:
                $str = 'odd';
                break;
            case $int%2 === 0:
                $str = 'even';
                break;
        }

        return $str;
    }
}
?>
