<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

class SkipInGetDefinedVars
{
    public function run()
    {
        $value = 'foobar';
        return get_defined_vars();
    }
}

?>
