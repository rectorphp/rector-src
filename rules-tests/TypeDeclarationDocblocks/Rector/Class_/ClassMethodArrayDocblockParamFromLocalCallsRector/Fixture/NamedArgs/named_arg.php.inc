<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture\NamedArgs;

final class CoverMixedAndKnown
{
    public function go()
    {
        $this->run(items: ['item1', 'item2']);
    }

    private function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture\NamedArgs;

final class CoverMixedAndKnown
{
    public function go()
    {
        $this->run(items: ['item1', 'item2']);
    }

    /**
     * @param string[] $items
     */
    private function run(array $items)
    {
    }
}

?>
