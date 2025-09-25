<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class FromExplode
{
    public function go()
    {
        $this->run(explode(':', '1:1'));
    }

    private function run(array $items)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class FromExplode
{
    public function go()
    {
        $this->run(explode(':', '1:1'));
    }

    /**
     * @param string[] $items
     */
    private function run(array $items)
    {
    }
}

?>
