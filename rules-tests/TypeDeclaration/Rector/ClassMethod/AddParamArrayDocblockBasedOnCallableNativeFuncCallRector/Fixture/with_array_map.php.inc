<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class WithArrayMap
{
    public function process(array $items)
    {
        array_map(function (stdClass $item): string {
            return $item->value;
        }, $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class WithArrayMap
{
    /**
     * @param \stdClass[] $items
     */
    public function process(array $items)
    {
        array_map(function (stdClass $item): string {
            return $item->value;
        }, $items);
    }
}

?>
