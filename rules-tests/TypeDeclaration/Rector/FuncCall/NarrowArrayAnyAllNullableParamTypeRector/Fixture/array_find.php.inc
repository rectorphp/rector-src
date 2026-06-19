<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function arrayFind(): ?string
{
    /** @var string[] $items */
    $items = ['a', 'b'];

    return array_find($items, fn (?string $item): bool => $item !== '');
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function arrayFind(): ?string
{
    /** @var string[] $items */
    $items = ['a', 'b'];

    return array_find($items, fn (string $item): bool => $item !== '');
}

?>
