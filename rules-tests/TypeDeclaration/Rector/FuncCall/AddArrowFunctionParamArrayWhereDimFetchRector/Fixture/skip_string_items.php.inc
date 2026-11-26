<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class SkipStringItems
{
    /**
     * @param array<int, string> $items
     */
    public function run(array $items)
    {
        return array_map(fn ($item) => $item[0], $items);
    }
}
