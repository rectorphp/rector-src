<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

function run()
{
    static $result = null;

    if (! isset($result)) {
        $result = 1;
        $temporaryValue = $result;
    }
}