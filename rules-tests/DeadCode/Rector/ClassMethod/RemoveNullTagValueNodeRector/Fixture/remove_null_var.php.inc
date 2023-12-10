<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class RemoveNullVar
{
    /**
     * @var null
     */
    public $var = null;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector\Fixture;

class RemoveNullVar
{
    public $var = null;
}

?>
