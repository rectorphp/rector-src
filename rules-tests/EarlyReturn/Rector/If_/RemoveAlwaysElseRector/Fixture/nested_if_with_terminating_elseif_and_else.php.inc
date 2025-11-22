<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

class NestedIfWithTerminatingElseIfAndElse
{
    public function run()
    {
        if ($cond1) {
            if ($cond2) {
                return 'foo';
            } elseif ($cond3) {
                return 'bar';
            } else {
                return 'baz';
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

class NestedIfWithTerminatingElseIfAndElse
{
    public function run()
    {
        if ($cond1) {
            if ($cond2) {
                return 'foo';
            }
            if ($cond3) {
                return 'bar';
            }
            return 'baz';
        }
        foo();
    }
}

?>
