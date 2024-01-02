<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

class Foo {
	public function bar() {
		$this->gaz();
	}

	private function gaz() {
	}
}

(new Foo())->bar();
