<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class OverrideMixed
{
    /**
     * @param array $items
     * @return mixed[]
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

final class OverrideMixed
{
    /**
     * @param array $items
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
