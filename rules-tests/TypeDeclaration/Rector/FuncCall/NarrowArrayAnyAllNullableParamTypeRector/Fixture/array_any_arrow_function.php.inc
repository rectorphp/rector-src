<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function arrayAnyArrowFunction(): bool
{
    /** @var string[] $items */
    $items = ['a', 'b'];

    return array_any($items, fn (?string $item): bool => $item !== '');
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function arrayAnyArrowFunction(): bool
{
    /** @var string[] $items */
    $items = ['a', 'b'];

    return array_any($items, fn (string $item): bool => $item !== '');
}

?>
