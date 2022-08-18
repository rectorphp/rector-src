<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SkipArrayDimFetch
{
    private int $temporaryValue;

    public function run()
    {
        $result = 100;

        $this->temporaryValue[] = $result;
    }
}
