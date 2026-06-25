<?php

namespace Rector\Tests\Php70\Rector\StmtsAwareInterface\IfIssetToCoalescingRector\Fixture;

class SkipMemoizeItemByKey
{
    private $items = [];

    public function resolve($key)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        return $this->items[$key] = 'fallback value';
    }
}
