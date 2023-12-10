<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class RemoveNullVar2
{
    public function foo()
    {
        /** @var null $var */
        $var = execute();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class RemoveNullVar2
{
    public function foo()
    {
        $var = execute();
    }
}

?>
