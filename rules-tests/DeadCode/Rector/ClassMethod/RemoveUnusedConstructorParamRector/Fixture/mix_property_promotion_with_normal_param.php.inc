<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

use stdClass;

final class MixPropertyPromotionWithNormalParam
{
	public function __construct(private stdClass $demofile, stdClass $std)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

use stdClass;

final class MixPropertyPromotionWithNormalParam
{
	public function __construct(private stdClass $demofile)
    {
    }
}

?>
