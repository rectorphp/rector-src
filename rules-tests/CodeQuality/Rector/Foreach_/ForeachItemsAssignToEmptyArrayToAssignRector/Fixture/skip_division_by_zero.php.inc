<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

class SkipDivisionByZero
{
    function run($mode)
    {
        $x = 1 / 0;
    }
}
