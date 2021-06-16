<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

class ElseIf_
{
    public function run()
    {
        if ($cond1) {
            return 'foo';
        }
        // a comment
        elseif ($cond2) {
            bar();
        }
        // a comment 2
        elseif ($cond3) {
            baz();
        } else {
            foo();
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

class ElseIf_
{
    public function run()
    {
        if ($cond1) {
            return 'foo';
        }
        // a comment
        if ($cond2) {
            bar();
        }
        // a comment 2
        elseif ($cond3) {
            baz();
        } else {
            foo();
        }
    }
}

?>
