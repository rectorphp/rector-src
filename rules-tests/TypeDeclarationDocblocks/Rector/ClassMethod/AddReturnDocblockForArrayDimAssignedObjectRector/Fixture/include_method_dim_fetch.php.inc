<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem;

final class IncludeMethodDimFetch
{
    public function provide(array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            $items[] = $this->createSomeItem();
        }

        return $items;
    }

    private function createSomeItem(): SomeItem
    {
        return new SomeItem();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem;

final class IncludeMethodDimFetch
{
    /**
     * @return \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector\Source\SomeItem[]
     */
    public function provide(array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            $items[] = $this->createSomeItem();
        }

        return $items;
    }

    private function createSomeItem(): SomeItem
    {
        return new SomeItem();
    }
}

?>
