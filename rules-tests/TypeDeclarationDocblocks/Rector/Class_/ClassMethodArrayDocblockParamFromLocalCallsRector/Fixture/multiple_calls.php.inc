<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class MultipleCalls
{
    public function go()
    {
        $this->run(['item1', 'item2']);

        $this->run(['item1', 'item2']);
    }

    private function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class MultipleCalls
{
    public function go()
    {
        $this->run(['item1', 'item2']);

        $this->run(['item1', 'item2']);
    }

    /**
     * @param string[] $items
     */
    private function run(array $items)
    {
    }
}

?>
