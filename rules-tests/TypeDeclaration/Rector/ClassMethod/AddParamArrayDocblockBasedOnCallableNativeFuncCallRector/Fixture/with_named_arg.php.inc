<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class WithNamedArg
{
    public function process(array $items): void
    {
        array_walk(callback: function (stdClass $item) {
            echo $item->value;
        }, array: $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class WithNamedArg
{
    /**
     * @param \stdClass[] $items
     */
    public function process(array $items): void
    {
        array_walk(callback: function (stdClass $item) {
            echo $item->value;
        }, array: $items);
    }
}

?>
