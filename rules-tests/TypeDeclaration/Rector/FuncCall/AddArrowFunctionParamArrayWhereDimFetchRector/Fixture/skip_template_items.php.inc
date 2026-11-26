<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class SkipTemplateItems
{
    /**
     * @template T
     * @param array<int, T> $items
     */
    public function run(array $items)
    {
        return array_map(fn ($item) => $item['id'], $items);
    }
}
