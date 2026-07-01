<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\NarrowArrayCollectionUnionReturnDocblockRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;

final class ArrayCollectionUnion
{
    private $successful;

    /**
     * @return LeadEventLog[]|ArrayCollection
     */
    public function getSuccessful(): ArrayCollection
    {
        return $this->successful;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\NarrowArrayCollectionUnionReturnDocblockRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;

final class ArrayCollectionUnion
{
    private $successful;

    /**
     * @return ArrayCollection<int, LeadEventLog>
     */
    public function getSuccessful(): ArrayCollection
    {
        return $this->successful;
    }
}

?>
