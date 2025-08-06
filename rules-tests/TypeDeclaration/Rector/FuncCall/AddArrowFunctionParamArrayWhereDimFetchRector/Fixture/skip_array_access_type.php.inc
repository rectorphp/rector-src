<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class SkipArrayAccessType
{
    /**
     * @param array<int, \ArrayAccess<string, int>> $items
     */
    public function run(array $items)
    {
        usort(
            $items,
            fn ($a, $b): int => $a['key'] <=> $b['key']
        );
    }
}
