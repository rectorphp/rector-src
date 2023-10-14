<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SkipUnions
{
    public function run()
    {
        $return = false;

        if (rand(0,1)) {
            $return = null;

            if (rand(2,1)) {
                $return = false;
            }

            if (rand(3,1)) {
                return $return;
            }

            return true;
        }

        if (rand(2,1)) {
            $return = true;
        }

        return $return;
    }
}

?>
