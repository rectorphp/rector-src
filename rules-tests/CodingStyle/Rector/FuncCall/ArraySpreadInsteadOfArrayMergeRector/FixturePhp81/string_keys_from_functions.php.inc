<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

function x(): array
{
    return ['b'=>1];
}
function y(): array
{
    return ['a'=>1];
}

class StringKeysFromFunctions
{
    public function run()
    {
        return array_merge(y(), x());
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

function x(): array
{
    return ['b'=>1];
}
function y(): array
{
    return ['a'=>1];
}

class StringKeysFromFunctions
{
    public function run()
    {
        return [...y(), ...x()];
    }
}

?>
