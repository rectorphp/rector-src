<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class NotZeroPosition
{
    public function go()
    {
        $this->run([], ['item1', 'item2']);
    }

    private function run(array $data, array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class NotZeroPosition
{
    public function go()
    {
        $this->run([], ['item1', 'item2']);
    }

    /**
     * @param string[] $items
     */
    private function run(array $data, array $items)
    {
    }
}

?>
