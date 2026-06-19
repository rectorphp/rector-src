<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function skipArrayFilter(): array
{
    /** @var string[] $items */
    $items = ['a', 'b'];

    return array_filter($items, fn (?string $item): bool => $item !== '');
}
