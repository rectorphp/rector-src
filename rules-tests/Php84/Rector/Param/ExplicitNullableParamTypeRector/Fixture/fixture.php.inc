<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class WithParamTypeNullDefault
{
    public function run(string $a = null)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class WithParamTypeNullDefault
{
    public function run(?string $a = null)
    {
    }
}

?>
