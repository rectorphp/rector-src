<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class SkipDocblockNullable
{
    /**
     * @param ?\stdClass $item
     */
    public function run($item)
    {
        if (!$item) {
            return 'empty';
        }
    }
}
