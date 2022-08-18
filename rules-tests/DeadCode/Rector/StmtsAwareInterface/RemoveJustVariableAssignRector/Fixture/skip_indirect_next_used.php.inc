<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SkipIndirectNextUsed
{
    public function run()
    {
        $result = execute();
        $this->temporaryValue = $result;

        otherCode();
        otherCode();

        function () use ($result) {

        };

        echo $result;
    }
}
