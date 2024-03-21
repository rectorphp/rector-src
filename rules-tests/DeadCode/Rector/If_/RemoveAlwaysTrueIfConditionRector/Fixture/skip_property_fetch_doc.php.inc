<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipPropertyFetchDoc
{
    /**
     * @var string
     */
    public $bodyFormat = '';

    public function run()
    {
        if (isset($this->bodyFormat) && $this->bodyFormat !== '') {
        }
    }

    public function reset()
    {
        $this->bodyFormat = null;
    }
}
