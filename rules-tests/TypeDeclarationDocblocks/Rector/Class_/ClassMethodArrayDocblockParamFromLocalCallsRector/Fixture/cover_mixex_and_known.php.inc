<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class CoverMixedAndKnown
{
    public function go($mixed)
    {
        $this->run(['item1', 'item2']);

        $this->run([$mixed, $mixed]);
    }

    private function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class CoverMixedAndKnown
{
    public function go($mixed)
    {
        $this->run(['item1', 'item2']);

        $this->run([$mixed, $mixed]);
    }

    /**
     * @param string[]|mixed[] $items
     */
    private function run(array $items)
    {
    }
}

?>
