<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipByParamDoc
{
    /**
     * @param \DateTime $param
     */
    public function verify($param)
    {
        if ($param instanceof \DateTime) {
            return true;
        }

        return false;
    }
}
