<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class SkipMaybeArrayAccessItem
{
    /**
     * @param array<int, array{id: int}|\ArrayAccess<string, int>> $items
     */
    public function run(array $items)
    {
        return array_filter($items, fn ($item) => $item['id'] ?? null);
    }
}
