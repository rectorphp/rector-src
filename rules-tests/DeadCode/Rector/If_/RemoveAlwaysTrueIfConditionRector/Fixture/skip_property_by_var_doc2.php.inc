<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

use Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Source\SomeClassWithPropertyUseVarDocblock;

class SkipPropertyByVarDoc2
{
    public function verify()
    {
        $obj = new SomeClassWithPropertyUseVarDocblock();
        if ($obj->property instanceof \DateTime) {
            return true;
        }

        return false;
    }
}
