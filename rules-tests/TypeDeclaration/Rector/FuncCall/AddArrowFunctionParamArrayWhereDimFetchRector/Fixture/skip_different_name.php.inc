<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class SkipDifferentName
{
    public function run()
    {
        $array = [['name' => 'John']];

        $result = array_map(fn ($item) => $hemen['name'], $array);
    }
}
