<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SkipUsedLater
{
    private int $temporaryValue;

    public function run()
    {
        $result = 100;

        $this->temporaryValue = $result;

        if ($result === 100) {
            return false;
        }
    }
}
