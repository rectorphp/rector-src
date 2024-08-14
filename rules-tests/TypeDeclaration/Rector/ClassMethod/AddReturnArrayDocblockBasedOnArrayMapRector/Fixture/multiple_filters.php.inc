<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class MultipleFilters
{
    public function run(array $items)
    {
        if (mt_rand(0, 1)) {
            return array_map(function ($item): int {
                return $item;
            }, $items);
        }

        return array_map(function ($item): int {
            return 1000;
        }, $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class MultipleFilters
{
    /**
     * @return int[]
     */
    public function run(array $items)
    {
        if (mt_rand(0, 1)) {
            return array_map(function ($item): int {
                return $item;
            }, $items);
        }

        return array_map(function ($item): int {
            return 1000;
        }, $items);
    }
}

?>
