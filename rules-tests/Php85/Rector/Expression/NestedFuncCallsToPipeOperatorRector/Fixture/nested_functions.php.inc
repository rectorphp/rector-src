<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

final class NestedFunctions
{
    public function run()
    {
        $result = trim(strtolower(htmlspecialchars('  Hello World!  ')));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

final class NestedFunctions
{
    public function run()
    {
        $result = '  Hello World!  '
            |> htmlspecialchars(...)
            |> strtolower(...)
            |> trim(...);
    }
}

?>
