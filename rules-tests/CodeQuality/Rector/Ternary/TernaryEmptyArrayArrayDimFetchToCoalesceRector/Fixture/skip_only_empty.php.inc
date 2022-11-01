<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\Fixture;

final class SkipOnlyEmpty
{
    private array $items = [];

    public function run()
    {
        return empty($this->items) ? $this->items[0] : 'default';
    }
}
