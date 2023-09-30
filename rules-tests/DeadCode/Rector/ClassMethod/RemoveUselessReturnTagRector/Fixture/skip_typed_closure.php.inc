<?php

declare(strict_types = 1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

interface X {
	/**
	 * @return \Closure(int): bool
	 */
	function createFilter(): \Closure;
}
?>
