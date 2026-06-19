<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class NullResetOfPlainObject
{
    public function run(): void
    {
        $value = new \stdClass();
        echo $value->name;

        $value = null;
    }
}
?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class NullResetOfPlainObject
{
    public function run(): void
    {
        $value = new \stdClass();
        echo $value->name;
    }
}
?>
