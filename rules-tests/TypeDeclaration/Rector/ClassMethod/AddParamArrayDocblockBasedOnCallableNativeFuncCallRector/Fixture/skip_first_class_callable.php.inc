<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

use stdClass;

final class SkipFirstClassCallable {
    function process(array $items): void
    {
        array_walk(...);
    }
}
