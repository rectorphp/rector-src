<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

final class SkipModifyOuterVariable
{
    public function postInc(array $items, array $others)
    {
        $i = 0;
        $found = null;
        foreach ($items as $idx => $item) {
            if ($item === $others[$i++]) {
                $found = $idx;
                break;
            }
        }

        return $found;
    }
}
