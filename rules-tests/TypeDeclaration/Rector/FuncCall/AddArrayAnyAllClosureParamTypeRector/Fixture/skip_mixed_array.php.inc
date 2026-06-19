<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayAnyAllClosureParamTypeRector\Fixture;

function skipMixedArray($items)
{
    return array_any($items, fn ($item): bool => $item !== '');
}
