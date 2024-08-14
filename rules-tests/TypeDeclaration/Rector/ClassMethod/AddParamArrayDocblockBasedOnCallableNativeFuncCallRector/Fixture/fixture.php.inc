<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class Fixture
{
    public function process(array $items): void
    {
        array_walk($items, function (stdClass $item) {
            echo $item->value;
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class Fixture
{
    /**
     * @param \stdClass[] $items
     */
    public function process(array $items): void
    {
        array_walk($items, function (stdClass $item) {
            echo $item->value;
        });
    }
}

?>
