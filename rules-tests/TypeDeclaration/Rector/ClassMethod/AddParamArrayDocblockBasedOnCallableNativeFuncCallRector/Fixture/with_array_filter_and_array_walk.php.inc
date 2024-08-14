<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class WithArrayFilterAndArrayWalk
{
    public function process(array $items): void
    {
        if (rand(0, 1)) {
            array_filter(
                $items,
                fn (string $str): bool => $str === 'a'
            );
            return;
        }

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

final class WithArrayFilterAndArrayWalk
{
    /**
     * @param string[]|\stdClass[] $items
     */
    public function process(array $items): void
    {
        if (rand(0, 1)) {
            array_filter(
                $items,
                fn (string $str): bool => $str === 'a'
            );
            return;
        }

        array_walk($items, function (stdClass $item) {
            echo $item->value;
        });
    }
}

?>