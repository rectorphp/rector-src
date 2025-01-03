<?php

namespace Rector\Custom\GYG\GeneralExceptionCatchRector\Fixture;

use Throwable;

class SomeClass
{
	public function run(): void
	{
		try {
			$this->someMethod();
		} catch (\Exception $e) {
			print $e;
		}
	}

	private function someMethod(): void
	{
		throw new \Exception('Some exception');
	}
}

?>
-----
<?php

namespace Rector\Custom\GYG\GeneralExceptionCatchRector\Fixture;

use Throwable;

class SomeClass
{
	public function run(): void
	{
		try {
			$this->someMethod();
		} catch (Throwable $e) {
			print $e;
		}
	}

	private function someMethod(): void
	{
		throw new \Exception('Some exception');
	}
}

?>
