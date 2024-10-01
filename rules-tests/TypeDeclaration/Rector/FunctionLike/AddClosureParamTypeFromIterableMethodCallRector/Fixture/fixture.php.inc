<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Source\Collection;

class Fixture
{
    /**
     * @param Collection<int, string> $collection
     */
    public function run(Collection $collection)
    {
        return $collection->map(function ($item, $key) {
            return $item;
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Source\Collection;

class Fixture
{
    /**
     * @param Collection<int, string> $collection
     */
    public function run(Collection $collection)
    {
        return $collection->map(function (string $item, int $key) {
            return $item;
        });
    }
}

?>
