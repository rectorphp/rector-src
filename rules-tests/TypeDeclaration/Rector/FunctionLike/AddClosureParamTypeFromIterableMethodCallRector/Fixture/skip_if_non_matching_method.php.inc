<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Source\Collection;

class Fixture
{
    /**
     * @param Collection<int> $collection
     */
    public function run(Collection $collection)
    {
        return $collection->each(function ($item) {
            return $item;
        });
    }
}
