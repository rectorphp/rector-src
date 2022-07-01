<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source\FastRunner;

class SkipVariableUsedInArrowFunctionArgs
{
    public function run()
    {
        $a = $this->getFastRunner(fn (FastRunner $a) => $a->run());
        $a->exit();
    }

    public function getFastRunner(callable $c): FastRunner
    {
        return new FastRunner();
    }
}
