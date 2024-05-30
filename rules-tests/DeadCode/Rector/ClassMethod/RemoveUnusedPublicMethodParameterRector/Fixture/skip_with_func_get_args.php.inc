<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

final class SkipWithFuncGetArgs
{
    public function run()
    {
        func_get_args();
    }
}

?>