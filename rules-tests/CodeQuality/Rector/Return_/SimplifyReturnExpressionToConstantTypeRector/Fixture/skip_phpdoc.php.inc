<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SkiPhpDoc
{
    /**
     * @param false $p
     */
    public function run($p)
    {
        if (rand(0,1)) {
            return true;
        }

        return $p;
    }
}

?>
