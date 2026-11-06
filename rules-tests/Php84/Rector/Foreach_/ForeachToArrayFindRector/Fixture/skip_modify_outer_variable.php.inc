<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindRector\Fixture;

final class SkipModifyOuterVariable
{
    public function postInc(array $items, array $others)
    {
        $i = 0;
        $found = null;
        foreach ($items as $item) {
            if ($item === $others[$i++]) {
                $found = $item;
                break;
            }
        }

        return $found;
    }
}
