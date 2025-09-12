<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class HandleProtectedMethodInNonFinal
{
    public function go()
    {
        $this->run(['item1', 'item2']);
    }

    protected function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class HandleProtectedMethodInNonFinal
{
    public function go()
    {
        $this->run(['item1', 'item2']);
    }

    /**
     * @param string[] $items
     */
    protected function run(array $items)
    {
    }
}

?>
