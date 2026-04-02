<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

class SkipAssignNoDiscardFunctionCall
{
    public function run()
    {
        $value = $this->some_call();
    }

    #[\NoDiscard]
    private function some_call()
    {

    }
}
