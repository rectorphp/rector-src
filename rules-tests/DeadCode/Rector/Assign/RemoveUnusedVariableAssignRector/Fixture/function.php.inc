<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

function unusedInFunction()
{
    $value = 5;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

function unusedInFunction()
{
}

?>
