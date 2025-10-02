<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class IncludeArrayMapNamed
{
    /**
     * @param int[] $items
     */
    public function run(array $items)
    {
        $result = array_map(array: $items, callback: fn ($item) => $item * 2);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class IncludeArrayMapNamed
{
    /**
     * @param int[] $items
     */
    public function run(array $items)
    {
        $result = array_map(array: $items, callback: fn (int $item) => $item * 2);
    }
}

?>
