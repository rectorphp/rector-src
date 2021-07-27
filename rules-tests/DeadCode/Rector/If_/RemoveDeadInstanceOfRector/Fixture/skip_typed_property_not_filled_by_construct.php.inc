<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use stdClass;

class SkipTypedPropertyNotFilledByConstruct
{
    private stdClass $var;

    public function init()
    {
        $this->var = new stdClass;
    }

    public function go()
    {
        if (! $this->var instanceof stdClass) {
            echo 'var is not an stdClass';
            return;
        }

        echo 'success' . PHP_EOL;
    }
}

?>
