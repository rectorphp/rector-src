<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

class MultipleElseifsWithNonTerminatingElseif
{
    public function run($a)
    {
        if ($a == 1) {
            return 1;
        } elseif ($a == 2) {
            return 2;
        } elseif ($a == 3) {
            echo 'Not returning';
        } elseif ($a == 4) {
            return 4;
        } elseif ($a == 5) {
            return 5;
        }
        return 'more';
    }
}
?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

class MultipleElseifsWithNonTerminatingElseif
{
    public function run($a)
    {
        if ($a == 1) {
            return 1;
        }
        if ($a == 2) {
            return 2;
        }
        if ($a == 3) {
            echo 'Not returning';
        }
        elseif ($a == 4) {
            return 4;
        }
        elseif ($a == 5) {
            return 5;
        }
        return 'more';
    }
}
?>
