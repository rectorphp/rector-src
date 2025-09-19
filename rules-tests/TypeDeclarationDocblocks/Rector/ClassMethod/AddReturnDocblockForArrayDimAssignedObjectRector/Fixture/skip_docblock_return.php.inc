<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem;

final class SkipDocblockReturn
{
    public function provide(array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            $items[] = $this->createSomeItem();
        }

        return $items;
    }

    /**
     * @return FakeItem
     */
    private function createSomeItem()
    {
        return new SomeItem();
    }
}
