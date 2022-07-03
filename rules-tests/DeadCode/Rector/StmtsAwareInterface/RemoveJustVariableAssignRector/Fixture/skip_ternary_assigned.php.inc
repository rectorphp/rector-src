<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SkipTernaryAssigned
{
    private int $temporaryValue;

    public function run()
    {
        $result = 100 ? 10 : 5;

        $this->temporaryValue = $result;
    }
}
