<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

class Fixture
{
    /**
     * @param string $a
     * @param string $b
     * @param string $c description
     */
    function foo(string $a, string $b, string $c)
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

class Fixture
{
    /**
     * @param $a
     * @param $b
     * @param $c description
     */
    function foo(string $a, string $b, string $c)
    {

    }
}

?>
