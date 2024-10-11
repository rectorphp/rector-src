<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

class SkipSameIfElseReturn
{
    public function run()
    {
        if (1 != count($obj->getChildren())) {
            return false;
        } else {
            return false;
        }
    }
}
