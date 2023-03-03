<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class SkipAssignInIfCond
{
    public function run()
    {
        if (($value = "foo") !== null) {
        	echo $value;
        }
    }
}
