<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SkipExtract
{
    public function run()
    {
        $return = 1;

        if (rand(0,1)) {
            return true;
        }

        $return++;

        return $return;
    }
}

?>
