<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipPossibleArrayAccessObject
{
    public function resolve($item)
    {
        $item->doSomething();

        return $item['name'];
    }
}
