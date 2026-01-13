<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddVarArrayDocblockFromDimFetchAssignRector\Fixture;

final class SomeClass
{
    private array $items = [];

    public function go()
    {
        $this->items[] = 'item1';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddVarArrayDocblockFromDimFetchAssignRector\Fixture;

final class SomeClass
{
    /**
     * @var string[]
     */
    private array $items = [];

    public function go()
    {
        $this->items[] = 'item1';
    }
}

?>
