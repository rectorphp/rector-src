<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Source;

class SomeLock
{
    public function __destruct()
    {
        // Do important things
    }
}
