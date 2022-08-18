<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SkipWithVarDoc
{
    public function run()
    {
        /** @var string $result */
        $result = execute();
        $this->temporaryValue = $result;
    }
}
