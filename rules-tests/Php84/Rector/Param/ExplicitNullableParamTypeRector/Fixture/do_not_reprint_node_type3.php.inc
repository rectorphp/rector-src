<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

class DoNotReprintNodeType3
{
    public function run(MockObject&stdClass $a = null)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

class DoNotReprintNodeType3
{
    public function run((MockObject&stdClass)|null $a = null)
    {
    }
}

?>
