<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

function foo(): array {}
if ($data = foo()) {
	foreach ($data as $row) {}
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

function foo(): array {}
$data = foo();
foreach ($data as $row) {}

?>
