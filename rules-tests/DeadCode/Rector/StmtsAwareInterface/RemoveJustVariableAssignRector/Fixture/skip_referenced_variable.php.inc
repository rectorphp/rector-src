<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SkipReferencedVariable
{
    private $temporaryValue;

    public function run(&$result)
    {
        $result = 100;

        $this->temporaryValue = $result;
    }
}
