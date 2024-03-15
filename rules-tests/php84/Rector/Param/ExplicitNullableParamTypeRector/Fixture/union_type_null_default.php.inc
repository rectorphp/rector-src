<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class UnionTypeNullDefault
{
    public function run(int|string $a = null)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class UnionTypeNullDefault
{
    public function run(int|string|null $a = null)
    {
    }
}

?>