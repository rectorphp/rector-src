<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

final class MultipleParams
{
    public function call($items)
    {
        usort($items, fn($a, $b) => strcmp($a, $b));
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

final class MultipleParams
{
    public function call($items)
    {
        usort($items, strcmp(...));
    }
}

?>
