<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

final class KeepUsedMethod {
	public function bar()
    {
		$this->gaz();
	}

	private function gaz() {
	}
}
