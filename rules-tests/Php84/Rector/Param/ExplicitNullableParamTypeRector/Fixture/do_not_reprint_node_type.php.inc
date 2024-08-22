<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

use stdClass;

class DoNotReprintNodeType
{
    public function run(stdClass $a = null)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

use stdClass;

class DoNotReprintNodeType
{
    public function run(?stdClass $a = null)
    {
    }
}

?>
