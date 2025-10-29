<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

final class ReturnNested
{
    public function run()
    {
        return trim(strtolower(htmlspecialchars('  Hello World!  ')));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

final class ReturnNested
{
    public function run()
    {
        return '  Hello World!  '
            |> htmlspecialchars(...)
            |> strtolower(...)
            |> trim(...);
    }
}

?>
