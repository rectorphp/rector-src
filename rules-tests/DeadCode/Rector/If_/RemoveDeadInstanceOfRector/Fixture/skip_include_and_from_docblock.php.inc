<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use PhpParser\Node;

final class SkipIncludeAndFromDocblock
{
    /**
     * @param Node $var
     */
    public function go($var)
    {
        if ($var instanceof Node && $var->getLine() === 100) {
        }
    }
}

