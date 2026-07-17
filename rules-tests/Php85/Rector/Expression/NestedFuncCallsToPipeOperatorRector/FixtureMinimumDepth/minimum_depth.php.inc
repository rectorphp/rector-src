<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\FixtureMinimumDepth;

final class MinimumDepth
{
    public function run(array $input)
    {
        $normalizedEmail = trim(strtolower(htmlspecialchars($input['email'])));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\FixtureMinimumDepth;

final class MinimumDepth
{
    public function run(array $input)
    {
        $normalizedEmail = $input['email']
            |> htmlspecialchars(...)
            |> strtolower(...)
            |> trim(...);
    }
}

?>
