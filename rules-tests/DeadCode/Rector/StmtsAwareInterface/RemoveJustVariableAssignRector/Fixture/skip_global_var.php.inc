<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

$result = 1;

function run()
{
    global $result;
    $result = 100;

    $temporaryValue = $result;
}

run();
echo $result;