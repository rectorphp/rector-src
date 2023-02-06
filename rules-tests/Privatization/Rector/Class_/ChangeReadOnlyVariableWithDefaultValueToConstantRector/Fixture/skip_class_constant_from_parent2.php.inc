<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\While_;

class Bar2
{
    protected const LOOP_NODES = [
        Do_::class,
        For_::class,
        While_::class,
    ];
}

class ClassConstantInsideCurrentClassAlreadyDefined3 extends Bar2
{
    public function run()
    {
        $loopNodes = self::LOOP_NODES;
        foreach ($loopNodes as $loopNode) {
            echo $loopNode;
        }
    }
}

(new ClassConstantInsideCurrentClassAlreadyDefined3())->run();