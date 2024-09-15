<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Source\NonIteratorClass;

class SkipNonIterableClass
{
    /**
     * @param NonIteratorClass<int> $collection
     */
    public function run(NonIteratorClass $collection)
    {
        return $collection->map(function ($item) {
            return $item;
        });
    }
}

?>
