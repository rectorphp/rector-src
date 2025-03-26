<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector\Fixture;

final class operator_precedence
{
	public function run()
	{
		$foo = rand(0,1);
		$bar = rand(0,1);

		if($foo and $bar) {
			$baz = '1';
		} else {
			$baz = '2';
		}
	}
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector\Fixture;

final class operator_precedence
{
	public function run()
	{
		$foo = rand(0,1);
		$bar = rand(0,1);

		$baz = ($foo and $bar) ? '1' : '2';
	}
}

?>
