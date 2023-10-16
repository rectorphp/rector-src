<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SkipStatic
{
    public function run()
    {
        static $return;

        if (rand(0,1)) {
            $return = false;
        }
        if (rand(0,2)) {
            $return = true;
        }

        return $return;
    }
}

?>
