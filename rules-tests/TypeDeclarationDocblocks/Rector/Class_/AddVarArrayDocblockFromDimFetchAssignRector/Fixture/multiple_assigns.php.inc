<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddVarArrayDocblockFromDimFetchAssignRector\Fixture;

final class MultipleAssigns
{
    private array $items = [];

    public function go()
    {
        $this->items[] = 'item1';
    }

    public function run()
    {
        $this->items[] = 'item2';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddVarArrayDocblockFromDimFetchAssignRector\Fixture;

final class MultipleAssigns
{
    /**
     * @var string[]
     */
    private array $items = [];

    public function go()
    {
        $this->items[] = 'item1';
    }

    public function run()
    {
        $this->items[] = 'item2';
    }
}

?>
