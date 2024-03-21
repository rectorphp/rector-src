<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class BooleanAndWithObject
{
    public function run(\stdClass $someObject)
    {
        if (is_object($someObject) && method_exists($someObject, 'some_method')) {
            return 100;
        }

        return 0;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

final class BooleanAndWithObject
{
    public function run(\stdClass $someObject)
    {
        if (method_exists($someObject, 'some_method')) {
            return 100;
        }

        return 0;
    }
}

?>

