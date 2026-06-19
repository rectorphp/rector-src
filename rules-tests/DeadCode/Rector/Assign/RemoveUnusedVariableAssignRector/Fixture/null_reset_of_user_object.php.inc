<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

use Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Source\SomeLock;

final class NullResetOfUserObject
{
    public function run(): void
    {
        $value = new SomeLock();
        echo get_class($value);

        $value = null;
    }
}
?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

use Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Source\SomeLock;

final class NullResetOfUserObject
{
    public function run(): void
    {
        $value = new SomeLock();
        echo get_class($value);
    }
}
?>
