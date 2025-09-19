<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem;

final class SkipPropertyFetchInCaseOfAnotherAssign
{
    public function provide(array $keys): array
    {
        $this->items = [];
        foreach ($keys as $key) {
            $this->items[] = new SomeItem($key);
        }

        return $this->items;
    }
}
