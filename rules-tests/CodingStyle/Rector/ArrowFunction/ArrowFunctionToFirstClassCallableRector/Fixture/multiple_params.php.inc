<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

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

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

final class MultipleParams
{
    public function call($items)
    {
        usort($items, strcmp(...));
    }
}

?>
