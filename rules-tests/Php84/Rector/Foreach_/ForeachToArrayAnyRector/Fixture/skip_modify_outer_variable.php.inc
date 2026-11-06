<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

final class SkipModifyOuterVariable
{
    public function postInc(array $items, array $others): bool
    {
        $i = 0;
        $found = false;
        foreach ($items as $item) {
            if ($item === $others[$i++]) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    public function earlyReturn(array $items, array $others): bool
    {
        $i = 0;
        foreach ($items as $item) {
            if ($item === $others[++$i]) {
                return true;
            }
        }

        return false;
    }
}
