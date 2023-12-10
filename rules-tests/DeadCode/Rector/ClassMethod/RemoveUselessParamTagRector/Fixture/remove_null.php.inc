<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class RemoveNull
{
    /**
     * @param null $a
     */
    public function foo(int $a = null)
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class RemoveNull
{
    public function foo(int $a = null)
    {

    }
}

?>
