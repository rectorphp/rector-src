<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\NarrowArrayCollectionUnionReturnDocblockRector\Fixture;

use Doctrine\Common\Collections\Collection;

final class ReversedOrder
{
    private $items;

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\NarrowArrayCollectionUnionReturnDocblockRector\Fixture;

use Doctrine\Common\Collections\Collection;

final class ReversedOrder
{
    private $items;

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
}

?>
