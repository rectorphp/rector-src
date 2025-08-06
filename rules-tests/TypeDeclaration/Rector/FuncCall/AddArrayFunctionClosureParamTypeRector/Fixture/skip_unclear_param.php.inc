<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class SkipUnclearParam
{
    /**
     * @param mixed[] $items
     */
    public function run(array $items)
    {
        $result = array_filter($items, fn ($item) => $item * 2);
    }
}
