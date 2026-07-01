<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\NarrowArrayCollectionUnionReturnDocblockRector\Fixture;

final class SkipNonCollectionUnion
{
    private $values;

    /**
     * @return LeadEventLog[]|SomeIterator
     */
    public function getValues(): iterable
    {
        return $this->values;
    }
}
