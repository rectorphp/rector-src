<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class SkipFirstClassCallable
{
    public function __construct(array $items)
    {
        return array_map(...);
    }
}
