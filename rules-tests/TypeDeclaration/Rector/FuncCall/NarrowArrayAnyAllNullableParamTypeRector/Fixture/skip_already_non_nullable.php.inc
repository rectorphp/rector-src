<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function skipAlreadyNonNullable(): bool
{
    /** @var string[] $items */
    $items = ['a', 'b'];

    return array_any($items, fn (string $item): bool => $item !== '');
}
