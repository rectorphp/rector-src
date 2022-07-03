<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SkipIndirectNextUsedAfterIf
{
    public function run()
    {
        if (rand(0, 1)) {
            $result = execute();
            $this->temporaryValue = $result;
        }

        echo $result ?? 'test';
    }
}
