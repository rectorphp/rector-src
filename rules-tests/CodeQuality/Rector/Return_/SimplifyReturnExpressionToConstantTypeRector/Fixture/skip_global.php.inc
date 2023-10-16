<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SkipGlobal
{
    public function run()
    {
        global $return;
        $return = false;

        if (rand(0,1)) {
            return true;
        }

        return $return;
    }
}

?>
