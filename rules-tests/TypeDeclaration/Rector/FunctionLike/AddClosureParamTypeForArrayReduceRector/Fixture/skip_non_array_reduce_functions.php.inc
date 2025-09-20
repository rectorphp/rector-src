<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Fixture;

function array_reduce(array $array, callable $func, $initial = null): array
{

}

class SkipNonArrayReduceFunctions
{
    /**
     * @param array<int, string> $array
     */
    public function run(array $array)
    {
        return \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture\array_reduce($array, function ($value, $key) {
            return $value . $key;
        });
    }
}

?>
