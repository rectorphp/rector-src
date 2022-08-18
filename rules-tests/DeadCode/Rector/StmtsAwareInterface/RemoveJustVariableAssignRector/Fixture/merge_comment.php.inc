<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SomeClass
{
    private int $temporaryValue;

    public function run()
    {
        // some comment
        $result = execute();

        // another comment
        $this->temporaryValue = $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustVariableAssignRector\Fixture;

final class SomeClass
{
    private int $temporaryValue;

    public function run()
    {
        // some comment
        // another comment
        $this->temporaryValue = execute();
    }
}

?>
