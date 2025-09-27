<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class UniqueTypes
{
    public function go()
    {
        $this->run(['item1', 'item2']);
        $this->run([1, 2]);
        $this->run(['item3', 'item4']);
    }

    private function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class UniqueTypes
{
    public function go()
    {
        $this->run(['item1', 'item2']);
        $this->run([1, 2]);
        $this->run(['item3', 'item4']);
    }

    /**
     * @param string[]|int[] $items
     */
    private function run(array $items)
    {
    }
}

?>
