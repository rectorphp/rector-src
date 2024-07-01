<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

use stdClass;

final class ReturnNoValueAndReturnValue2
{
    /**
     * @return stdClass|void
     */
    public function add()
    {
		if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            return;
        }

		return new stdClass();
	}
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

use stdClass;

final class ReturnNoValueAndReturnValue2
{
    /**
     * @return stdClass|null
     */
    public function add()
    {
		if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            return null;
        }

		return new stdClass();
	}
}

?>
