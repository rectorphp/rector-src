<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class SkipTypedDoc {
    /**
     * @param stdClass[] $items
     */
    function process(array $items): void
    {
        array_walk($items, function (stdClass $item) {
            echo $item->value;
        });
    }
}
