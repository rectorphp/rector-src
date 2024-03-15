<?php

namespace Rector\Tests\php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class UnionTypeNullDefault
{
    public function run(int|string $a = null)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class UnionTypeNullDefault
{
    public function run(int|string|null $a = null)
    {
    }
}

?>