<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamArrayDocblockBasedOnCallableNativeFuncCallRector\Fixture;

final class SkipMixed {
    function process(array $items, $callable): void
    {
        array_walk($items, $callable);
    }
}
