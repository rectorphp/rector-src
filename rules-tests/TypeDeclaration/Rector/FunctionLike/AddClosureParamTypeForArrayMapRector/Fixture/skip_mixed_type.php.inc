<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector\Fixture;

class SkipMixedType
{
    /**
     * @param array<int, mixed> $array
     */
    public function run(array $array)
    {
        return array_map(function ($value) {
            return $value;
        }, $array);
    }
}

?>
