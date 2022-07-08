<?php

namespace Rector\Tests\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector\Fixture;

use stdClass;

function run()
{
    static $result = null;

    if (! isset($result)) {
        $result = new stdClass();
    }

    return $result;
}

