<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

use Rector\NodeNestingScope\ContextAnalyzer;

class SkipClassConstantFromArrayClass
{
    public function run()
    {
        $loopNodes = ContextAnalyzer::LOOP_NODES;
        foreach ($loopNodes as $loopNode) {

        }
    }
}
