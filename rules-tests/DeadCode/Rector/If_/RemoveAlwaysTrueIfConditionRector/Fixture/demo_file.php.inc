<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class DemoFile
{
    public function run()
    {
        if (($value = "foo") !== null) {
        	echo $value;
        }
    }
}

?>
-----
