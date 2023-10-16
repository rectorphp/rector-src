<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SkipByRefParam
{
    public function run(bool &$b)
    {
        if ($b) {
            if (rand(0,1)) {

            }

            return $b;
        }

        if (rand(0,1)) {

        }

        return $b;
    }
}

?>
