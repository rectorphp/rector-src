<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class SkipFilledType
{
    public function run()
    {
        $array = [['name' => 'John']];

        $result = array_map(fn (iterable $item) => $item['name'], $array);
    }
}
