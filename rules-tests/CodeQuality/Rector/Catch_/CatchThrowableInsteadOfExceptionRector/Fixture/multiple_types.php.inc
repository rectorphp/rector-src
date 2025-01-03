<?php

namespace Rector\Custom\GYG\GeneralExceptionCatchRector\Fixture;

use ArithmeticError;
use Exception;

class SomeClass
{
	public function run(): void
	{
		try {
			$this->someMethod();
		} catch (ArithmeticError|Exception $e) {
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

use Throwable;
use ArithmeticError;
use Exception;

class SomeClass
{
	public function run(): void
	{
		try {
			$this->someMethod();
		} catch (ArithmeticError|Throwable $e) {
			print $e;
		}
	}

	private function someMethod(): void
	{
		throw new \UnexpectedValueException('Some exception');
	}
}

?>
