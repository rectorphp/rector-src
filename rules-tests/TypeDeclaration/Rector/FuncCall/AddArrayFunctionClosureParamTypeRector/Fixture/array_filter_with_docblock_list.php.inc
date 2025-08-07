<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class ArrayFilterWithDocblockListType
{
    /**
     * @param list<int> $items
     */
    public function run(array $items)
    {
        $result = array_filter($items, fn ($item) => $item * 2);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class ArrayFilterWithDocblockListType
{
    /**
     * @param list<int> $items
     */
    public function run(array $items)
    {
        $result = array_filter($items, fn (int $item) => $item * 2);
    }
}

?>
