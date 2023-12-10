<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class RemoveNullParam
{
    /**
     * @param null $foo
     */
    function foo($foo = null)
    {
        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class RemoveNullParam
{
    function foo($foo = null)
    {
        return null;
    }
}

?>
