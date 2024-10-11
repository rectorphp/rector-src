<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

function array_map(callable $func, array $array)
{

}

class SkipNonArrayMapFunctions
{
    /**
     * @param array<int, string> $array
     */
    public function run(array $array)
    {
        return \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture\array_map(function ($value, $key) {
            return $value . $key;
        }, $array);
    }
}

?>
