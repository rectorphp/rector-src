<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class DoWhileMaybeReturned3
{
    public function run(int $i)
    {
		do {
    		if (rand(0,1)) {
                return 1;
    		}
		} while (++$i < 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class DoWhileMaybeReturned3
{
    public function run(int $i)
    {
		do {
    		if (rand(0,1)) {
                return 1;
    		}
		} while (++$i < 1);
  return null;
    }
}

?>
