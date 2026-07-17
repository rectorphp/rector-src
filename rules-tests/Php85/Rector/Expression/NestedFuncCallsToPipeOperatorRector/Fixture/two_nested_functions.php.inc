<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

final class TwoNestedFunctions
{
    public function run(array $input)
    {
        assert(is_string($input['email']));
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

final class TwoNestedFunctions
{
    public function run(array $input)
    {
        $input['email']
            |> is_string(...)
            |> assert(...);
    }
}

?>
