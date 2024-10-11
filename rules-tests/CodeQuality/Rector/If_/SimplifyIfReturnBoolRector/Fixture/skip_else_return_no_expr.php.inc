<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

class SkipElseReturnNoExpr
{
    public function run()
    {
        if (1 != count($obj->getChildren())) {
            return false;
        } else {
            return;
        }

        return true;
    }
}
