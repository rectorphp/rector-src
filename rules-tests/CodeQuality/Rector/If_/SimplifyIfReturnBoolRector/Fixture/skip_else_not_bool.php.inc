<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

class SkipElseNotBool
{
    public function run()
    {
        if (1 != count($obj->getChildren())) {
            return false;
        } else {
            return '1';
        }

        // while unreachable, above code is valid and should not be simplified by this rule
        // the dead code set can remove unreachable stmt part
        // if combined with early return set, it will be cleared up :)
        // @see https://getrector.com/demo/a4345e26-d168-4fc3-802d-eb813ad09408
        // @see https://getrector.com/demo/74c759c0-3cc7-4ed0-a55d-34fb7d1e3c24
        return true;
    }
}
