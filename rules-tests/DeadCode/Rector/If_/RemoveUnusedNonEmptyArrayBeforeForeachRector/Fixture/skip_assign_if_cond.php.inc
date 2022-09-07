<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

function foo(): array {}
// a comment
if ($data = foo()) {
	foreach ($data as $row) {}
}
