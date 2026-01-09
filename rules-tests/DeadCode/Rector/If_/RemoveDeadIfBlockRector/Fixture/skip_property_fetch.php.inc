<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class SkipPropertyFetch
{
    public function run($obj, $condition)
    {
        if ($condition) {
            echo '1';
        } elseif ($obj->v) {
            echo '2';
        }
    }
}

?>
