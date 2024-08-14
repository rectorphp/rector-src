<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use DateTime;
use stdClass;

final class DifferentUsage {
    public function process(array $items): void
    {
        if (rand(0, 1)) {
            array_walk($items, function (stdClass $item) {
                echo $item->value;
            });

            return;
        }

        array_walk($items, function (DateTime $item) {
            echo $item->format('Y-m-d');
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use DateTime;
use stdClass;

final class DifferentUsage {
    /**
     * @param \stdClass[]|\DateTime[] $items
     */
    public function process(array $items): void
    {
        if (rand(0, 1)) {
            array_walk($items, function (stdClass $item) {
                echo $item->value;
            });

            return;
        }

        array_walk($items, function (DateTime $item) {
            echo $item->format('Y-m-d');
        });
    }
}

?>