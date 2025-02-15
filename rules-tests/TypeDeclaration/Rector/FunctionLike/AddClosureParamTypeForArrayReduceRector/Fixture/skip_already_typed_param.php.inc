<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector\Fixture;

class SkipAlreadyTypedParam
{
    /**
     * @param list<string> $array
     */
    public function run(array $array)
    {
        return array_reduce($array, function (string $carry, ?string $value) {
            return $carry . $value;
        }, '');
    }
}
