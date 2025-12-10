<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

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

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class MultipleParams
{
    public function call($items)
    {
        usort($items, strcmp(...));
    }
}

?>
