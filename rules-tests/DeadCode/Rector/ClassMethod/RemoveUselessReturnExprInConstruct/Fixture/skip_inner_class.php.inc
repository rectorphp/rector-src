<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstruct\Fixture;

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
