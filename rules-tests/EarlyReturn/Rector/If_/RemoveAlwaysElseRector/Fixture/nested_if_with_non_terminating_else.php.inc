<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

class NestedIfWithNonTerminatingElse
{
    public function run()
    {
        if ($cond1) {
            if ($cond2) {
                return 'foo';
            } else {
                echo 'Not returning';
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

class NestedIfWithNonTerminatingElse
{
    public function run()
    {
        if ($cond1) {
            if ($cond2) {
                return 'foo';
            }
            echo 'Not returning';
        } else {
            foo();
        }
    }
}

?>
