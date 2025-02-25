<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

class SkipAlreadyParamTyped
{
    /**
     * @param array<int, string> $array
     */
    public function run(array $array)
    {
        return array_map(function (string $value, int|string $key) {
            return $value . $key;
        }, $array);
    }
}

?>
