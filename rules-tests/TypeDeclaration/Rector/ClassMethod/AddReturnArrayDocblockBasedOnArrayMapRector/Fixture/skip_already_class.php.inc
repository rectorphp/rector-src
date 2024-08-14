<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector\Fixture;

use stdClass;

final class SkipAlreadyClass
{
    /**
     * @return stdClass[]
     */
    public function process(array $items)
    {
        return array_map(function ($item): stdClass {
            return $item;
        }, $items);
    }
}
