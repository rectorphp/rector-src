<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Source\NonTargetedClass;

class Fixture
{
    /**
     * @param NonTargetedClass<int> $collection
     */
    public function run(NonTargetedClass $collection)
    {
        return $collection->map(function ($item) {
            return $item;
        });
    }
}
