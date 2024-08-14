<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class ImproveSimpleArray
{
    /**
     * @return array
     */
    public function process(array $items)
    {
        return array_map(function ($item): int {
            return $item;
        }, $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class ImproveSimpleArray
{
    /**
     * @return int[]
     */
    public function process(array $items)
    {
        return array_map(function ($item): int {
            return $item;
        }, $items);
    }
}

?>
