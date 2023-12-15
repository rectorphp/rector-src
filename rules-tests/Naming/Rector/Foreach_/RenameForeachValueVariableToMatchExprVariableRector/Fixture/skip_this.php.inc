<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector\Fixture;

class SkipThis
{
    public function run()
    {
        foreach ($this as $item) {
        }
    }
}

?>
