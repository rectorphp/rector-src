<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class MultipleCallsWithVariousTypes
{
    public function go()
    {
        $this->run(['item1', 'item2']);
        $this->run([123, 456]);
    }

    private function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class MultipleCallsWithVariousTypes
{
    public function go()
    {
        $this->run(['item1', 'item2']);
        $this->run([123, 456]);
    }

    /**
     * @param string[]|int[] $items
     */
    private function run(array $items)
    {
    }
}

?>
