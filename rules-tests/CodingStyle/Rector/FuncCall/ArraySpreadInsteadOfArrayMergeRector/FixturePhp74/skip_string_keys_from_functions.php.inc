<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

function x(): array
{
    return ['b'=>1];
}
function y(): array
{
    return ['a'=>1];
}

class SkipStringKeysFromFunctions
{
    public function run()
    {
        return array_merge(y(), x());
    }
}
