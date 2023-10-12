<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstructRector\Fixture;

final class SkipInnerClass
{
    public function __construct()
    {
        $obj = new class {
            public function run()
            {
                return true;
            }
        };
    }
}
