<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class UnionUnsorted
{
    function foo(string|int|bool $param)
    {

    }
}

?>
