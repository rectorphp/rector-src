<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SkipExtract
{
    public function run($data)
    {
        $return = false;

        if (rand(0,1)) {
            return true;
        }

        extract($data);

        return $return;
    }
}

?>
