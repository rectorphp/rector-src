<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem;

final class SkipDimValue
{
    public function provide(array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            $items[1000] = new SomeItem($key);
        }

        return $items;
    }
}
