<?php

namespace Rector\Tests\CodeQuality\Rector\Switch_\SingularSwitchToIfRector\Fixture;

final class SkipNestedBreak
{
    public function run($a, $b, $else, $value)
    {
        switch ($a) {
            case $b:
                $something = $else;
                if (! $something) {
                    break;
                }
                $another = $value;
                break;
        }
    }
}

?>
