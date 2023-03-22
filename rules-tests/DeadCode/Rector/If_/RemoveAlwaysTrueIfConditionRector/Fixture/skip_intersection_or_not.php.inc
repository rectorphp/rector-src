<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

use Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Source\Foo;
use Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Source\Bar;

function testIt((Foo&Bar)|null $fooBar = null) {
	if ($fooBar === null) {
        $fooBar = new Foo();
    }

    return $fooBar;
}

?>
