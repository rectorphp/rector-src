<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use stdClass;

function get(): stdClass
{
    session_start();

    return new stdClass;
}

class SkipDirectFunctionCall
{
    public function go()
    {
        if (! $this->get() instanceof stdClass) {
            return false;
        }

        return true;
    }
}

?>