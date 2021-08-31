<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector\Fixture;

class SkipSameReturn
{
    public function run($a, $b)
    {
        if ($a || $b) {
            return null;
        }
        return null;
    }
}

?>
