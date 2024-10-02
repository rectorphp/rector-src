<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Source\Collection;

class SkipMissingIterableTypes
{
    /**
     * @param Collection $collection
     */
    public function run(Collection $collection)
    {
        return $collection->map(function ($item, $key) {
            return $item . $key;
        });
    }
}

?>
