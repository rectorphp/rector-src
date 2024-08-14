<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class MultipleTypes
{
    public function run(array $items)
    {
        if (mt_rand(0, 1)) {
            return array_map(function ($item): int {
                return $item;
            }, $items);
        }

        return array_map(function ($item): string {
            return '1000';
        }, $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class MultipleTypes
{
    /**
     * @return int[]|string[]
     */
    public function run(array $items)
    {
        if (mt_rand(0, 1)) {
            return array_map(function ($item): int {
                return $item;
            }, $items);
        }

        return array_map(function ($item): string {
            return '1000';
        }, $items);
    }
}

?>
