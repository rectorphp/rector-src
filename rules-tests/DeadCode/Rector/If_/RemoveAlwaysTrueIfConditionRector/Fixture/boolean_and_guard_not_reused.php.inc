<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class BooleanAndGuardNotReused
{
    public function run(\stdClass $someObject, bool $flag)
    {
        if (is_object($someObject) && $flag) {
            return 100;
        }

        return 0;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class BooleanAndGuardNotReused
{
    public function run(\stdClass $someObject, bool $flag)
    {
        if ($flag) {
            return 100;
        }

        return 0;
    }
}

?>
