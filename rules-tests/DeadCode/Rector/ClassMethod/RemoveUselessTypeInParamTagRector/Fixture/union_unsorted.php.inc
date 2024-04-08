<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

class UnionUnsorted
{
    /**
     * @param bool|int|string $param
     */
    function foo(string|int|bool $param)
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

class UnionUnsorted
{
    /**
     * @param $param
     */
    function foo(string|int|bool $param)
    {

    }
}

?>
