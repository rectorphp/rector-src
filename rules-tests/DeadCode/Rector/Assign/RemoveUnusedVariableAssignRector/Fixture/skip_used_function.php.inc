<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

function usedInFunction()
{
    $value = 5;

    if (rand(0,1)) {
        $value += 10;
    }
}

?>
