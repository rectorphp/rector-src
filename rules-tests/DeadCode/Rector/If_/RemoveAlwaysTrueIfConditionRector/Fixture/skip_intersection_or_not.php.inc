<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class Foo {}

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class Bar {}

function testIt((Foo&Bar)|null $fooBar = null) {
	if ($fooBar === null) {
        $fooBar = new Foo();
    }
    
    return $fooBar;
}

?>
