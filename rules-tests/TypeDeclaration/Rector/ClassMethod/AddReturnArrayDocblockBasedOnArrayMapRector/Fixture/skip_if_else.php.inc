<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

final class SkipIfElse
{
    public function __construct(array $items)
    {
        if (rand(0, 1)) {
            return array_map(function ($item): int {
                return $item;
            }, $items);
        }

        return [];
    }
}
