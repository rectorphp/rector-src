<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem;

final class SkipDefaultAssign
{
    public function provide(array $keys): array
    {
        $items = [1000];
        foreach ($keys as $key) {
            $items[] = new SomeItem($key);
        }

        return $items;
    }
}
