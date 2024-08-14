<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class SkipReAssign {
    function process(array $items): void
    {
        $items = new stdClass;
        $items->one = new stdClass;
        $items->two = new stdClass;

        array_walk($items, function (stdClass $item) {
            echo $item->value;
        });
    }
}
