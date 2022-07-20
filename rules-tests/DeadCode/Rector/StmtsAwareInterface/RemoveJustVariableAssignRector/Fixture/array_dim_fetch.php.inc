<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SomeArrayDimFetch
{
    private int $temporaryValue;

    public function run()
    {
        $result = 100;

        $this->temporaryValue[] = $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SomeArrayDimFetch
{
    private int $temporaryValue;

    public function run()
    {
        $this->temporaryValue[] = 100;
    }
}

?>
