<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class RemoveNull
{
    /**
     * @return null
     */
    function foo()
    {
        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class RemoveNull
{
    function foo()
    {
        return null;
    }
}

?>
