<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function skipNullableItemType(): bool
{
    /** @var array<string|null> $items */
    $items = ['a', null];

    return array_any($items, fn (?string $item): bool => $item !== '');
}
