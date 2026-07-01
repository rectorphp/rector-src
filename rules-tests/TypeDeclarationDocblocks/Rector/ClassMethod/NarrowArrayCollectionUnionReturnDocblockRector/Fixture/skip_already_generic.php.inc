<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\NarrowArrayCollectionUnionReturnDocblockRector\Fixture;

use Doctrine\Common\Collections\ArrayCollection;

final class SkipAlreadyGeneric
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
