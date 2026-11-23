<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

final class SkipSeveralArrays
{
    /**
     * array_map() pads the shorter array with null
     *
     * @param array<int, string> $array
     * @param array<string> $arrayTwo
     */
    public function run(array $array, array $arrayTwo)
    {
        return array_map(function ($value, $second) {
            return $second . $value;
        }, $array, $arrayTwo);
    }
}
