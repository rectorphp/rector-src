<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class DoWhileMaybeReturned2
{
    public function run(int $i)
    {
		do {
    		if (rand(0,1)) {
                break;
    		}

		    return 2;
		} while (++$i < 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class DoWhileMaybeReturned2
{
    public function run(int $i)
    {
		do {
    		if (rand(0,1)) {
                break;
    		}

		    return 2;
		} while (++$i < 1);
        return null;
    }
}

?>
