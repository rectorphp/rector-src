<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Stmt;

class SkipUnionDocWithSubType2
{
    /**
     * @return null|Stmt[]|Node
     */
    function foo(): null|array|Node
    {
    }
}

?>