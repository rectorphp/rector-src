<?php

namespace Rector\Custom\GYG\GeneralExceptionCatchRector\Fixture;

class SomeClass
{
	public function run(): void
	{
		try {
			$this->someMethod();
		} catch (\UnexpectedValueException $e) {
			print $e;
		}
	}

	private function someMethod(): void
	{
		throw new \UnexpectedValueException('Some exception');
	}
}

?>
-----
<?php

namespace Rector\Custom\GYG\GeneralExceptionCatchRector\Fixture;

class SomeClass
{
	public function run(): void
	{
		try {
			$this->someMethod();
		} catch (\UnexpectedValueException $e) {
			print $e;
		}
	}

	private function someMethod(): void
	{
		throw new \UnexpectedValueException('Some exception');
	}
}

?>
