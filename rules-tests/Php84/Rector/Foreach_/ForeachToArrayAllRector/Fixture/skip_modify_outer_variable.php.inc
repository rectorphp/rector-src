<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

final class SkipModifyOuterVariable
{
    public function postInc(array $items, array $others): bool
    {
        $i = 0;
        $equals = true;
        foreach ($items as $item) {
            if ($item !== $others[$i++]) {
                $equals = false;
                break;
            }
        }

        return $equals;
    }

    public function assignOp(array $items, int $limit): bool
    {
        $sum = 0;
        $valid = true;
        foreach ($items as $item) {
            if (($sum += $item) > $limit) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }

    public function earlyReturn(array $items, array $others): bool
    {
        $i = 0;
        foreach ($items as $item) {
            if ($item !== $others[$i++]) {
                return false;
            }
        }

        return true;
    }
}
