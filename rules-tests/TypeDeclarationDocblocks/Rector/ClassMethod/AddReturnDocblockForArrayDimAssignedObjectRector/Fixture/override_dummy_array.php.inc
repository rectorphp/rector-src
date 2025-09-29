<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem;

final class OverrideDummyArray
{
    /**
     * @return array
     */
    public function provide(array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            $items[] = new SomeItem($key);
        }

        return $items;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem;

final class OverrideDummyArray
{
    /**
     * @return \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem[]
     */
    public function provide(array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            $items[] = new SomeItem($key);
        }

        return $items;
    }
}

?>
