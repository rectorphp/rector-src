<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class EmptyArrayNoType
{
    public function go($mixed)
    {
        $this->run(['item1', 'item2']);
        $this->run([]);
    }

    private function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class EmptyArrayNoType
{
    public function go($mixed)
    {
        $this->run(['item1', 'item2']);
        $this->run([]);
    }

    /**
     * @param string[] $items
     */
    private function run(array $items)
    {
    }
}

?>
