<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\Fixture;

final class SkipNonArray
{
    private $items;

    public function run()
    {
        return ! empty($this->items) ? $this->items[0] : 'default';
    }
}
