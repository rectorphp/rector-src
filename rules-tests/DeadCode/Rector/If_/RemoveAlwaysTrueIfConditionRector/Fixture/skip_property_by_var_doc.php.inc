<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipPropertyByVarDoc
{
    /** @var \DateTime */
    private $property;

    public function __construct()
    {
        if (rand(0, 1)) {
            $this->property = new \DateTime('now');
        }
    }

    public function verify()
    {
        if ($this->property instanceof \DateTime) {
            return true;
        }

        return false;
    }
}
