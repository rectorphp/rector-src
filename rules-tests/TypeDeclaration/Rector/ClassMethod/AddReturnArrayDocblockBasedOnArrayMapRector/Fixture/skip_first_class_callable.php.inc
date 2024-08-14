<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class SkipFirstClassCallable
{
    public function run(array $items)
    {
        return array_map(...);
    }
}
