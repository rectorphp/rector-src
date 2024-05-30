<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

final class WithParamDoc
{
    /**
     * @param string $a
     * @param string $b
     */
    public function run($a, $b)
    {
        echo $a;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

final class WithParamDoc
{
    /**
     * @param string $a
     */
    public function run($a)
    {
        echo $a;
    }
}

?>
