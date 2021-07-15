<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedAssignVariableRector\Fixture;

class SkipInCompact
{
    public function run()
    {
        $value = 'foobar';
        return compact('value');
    }
}
