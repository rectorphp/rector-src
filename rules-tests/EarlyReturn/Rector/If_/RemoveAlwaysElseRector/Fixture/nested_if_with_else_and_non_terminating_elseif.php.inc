<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

class NestedIfWithElseAndNonTerminatingElseIf
{
    public function run()
    {
        if ($cond1) {
            if ($cond2) {
                return 'foo';
            } elseif ($cond3) {
                echo 'Not returning';
            } else {
                return 'bar';
            }
        } else {
            foo();
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

class NestedIfWithElseAndNonTerminatingElseIf
{
    public function run()
    {
        if ($cond1) {
            if ($cond2) {
                return 'foo';
            }
            if ($cond3) {
                echo 'Not returning';
            }
            else {
                return 'bar';
            }
        } else {
            foo();
        }
    }
}

?>
