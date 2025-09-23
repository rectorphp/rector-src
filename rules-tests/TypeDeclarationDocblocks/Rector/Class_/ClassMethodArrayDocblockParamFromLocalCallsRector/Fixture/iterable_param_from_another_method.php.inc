<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class IterableParamFromAnotherMethod
{
    /**
     * @param string[] $items
     */
    public function go(array $items)
    {
        $this->run($items);
    }

    private function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class IterableParamFromAnotherMethod
{
    /**
     * @param string[] $items
     */
    public function go(array $items)
    {
        $this->run($items);
    }

    /**
     * @param string[] $items
     */
    private function run(array $items)
    {
    }
}

?>
