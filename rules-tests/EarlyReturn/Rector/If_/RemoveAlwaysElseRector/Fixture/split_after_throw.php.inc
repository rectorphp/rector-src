<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

final class SplitAfterThrow
{
    public function run()
    {
        if ($cond1) {
            throw new \Exception('should not happen');
        } else {
            foo();
            return 'bar';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\RemoveAlwaysElseRector\Fixture;

final class SplitAfterThrow
{
    public function run()
    {
        if ($cond1) {
            throw new \Exception('should not happen');
        }
        foo();
        return 'bar';
    }
}

?>
