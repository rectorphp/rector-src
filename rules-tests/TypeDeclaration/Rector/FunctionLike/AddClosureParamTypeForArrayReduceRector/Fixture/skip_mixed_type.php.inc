<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Fixture;

class SkipMixedType
{
    /**
     * @param array<int, mixed> $array
     */
    public function run(array $array)
    {
        return array_reduce($array, function ($carry, $value) {
            return $value->foo($carry);
        }, '');
    }
}

?>
