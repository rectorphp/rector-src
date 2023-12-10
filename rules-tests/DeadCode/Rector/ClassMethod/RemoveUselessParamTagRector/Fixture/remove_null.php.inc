<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class RemoveNull
{
    /**
     * @param null $a
     */
    public function foo($a = null)
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class RemoveNull
{
    public function foo($a = null)
    {

    }
}

?>
