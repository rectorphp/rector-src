<?php

namespace Rector\Tests\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector\Fixture;

$value = 0;

function run(int $value)
{
    global $value;

    if ($value === 50) {
        $value = 1;
    }

    return $value;
}
