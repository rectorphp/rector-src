<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class IncludeArrayMap
{
    /**
     * @param int[] $items
     */
    public function run(array $items)
    {
        $result = array_map(fn ($item) => $item * 2, $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class IncludeArrayMap
{
    /**
     * @param int[] $items
     */
    public function run(array $items)
    {
        $result = array_map(fn (int $item) => $item * 2, $items);
    }
}

?>
