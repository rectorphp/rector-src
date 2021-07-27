<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use stdClass;

function sideEffect(): stdClass
{
    session_start();

    return new stdClass;
}

class SkipDirectFunctionCall
{
    public function go()
    {
        if (! $this->sideEffect() instanceof stdClass) {
            return false;
        }

        return true;
    }
}

?>