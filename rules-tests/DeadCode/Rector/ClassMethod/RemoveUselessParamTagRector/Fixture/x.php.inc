<?php

declare(strict_types = 1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class X {
	/**
	 * @param \Closure(int): bool $filter
	 */
	static function filter(\Closure $filter): array {
	}
}
?>
