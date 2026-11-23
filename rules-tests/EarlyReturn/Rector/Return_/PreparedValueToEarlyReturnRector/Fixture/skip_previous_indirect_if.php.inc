<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

class SkipPreviousIndirectIf
{
    public function run($var)
    {
        $var = null;
        echo 'a statement';
        if (rand(0,1)) {
            $var = 1 ;
        }

        if (rand(0,2)) {
            $var = 2 ;
        }

        return $var;
    }
}

?>
