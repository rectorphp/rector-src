<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

final class ArrayMap
{
    public function call($items)
    {
        array_map(fn($x) => strlen($x), $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

final class ArrayMap
{
    public function call($items)
    {
        array_map(strlen(...), $items);
    }
}

?>
