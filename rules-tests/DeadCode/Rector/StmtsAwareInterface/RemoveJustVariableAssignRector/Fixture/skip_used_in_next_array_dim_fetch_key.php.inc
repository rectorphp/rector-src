<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SkipUsedInNextArrayDimFetchKey
{
    private array $temporaryValue;

    public function run()
    {
        $result = 100;

        $this->temporaryValue[(string) $result] = $result;
    }
}
