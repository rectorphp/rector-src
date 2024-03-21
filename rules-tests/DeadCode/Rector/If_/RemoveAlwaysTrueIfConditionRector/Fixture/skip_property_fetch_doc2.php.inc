<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipPropertyFetchDoc2
{
    /**
     * @var array<int, int>
     */
    public $data = [];

    public function run($key)
    {
        if (is_int($this->data[$key]) && $this->data[$key] > 0) {
        }
    }
}
