<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayAnyAllClosureParamTypeRector\Fixture;

function arrayAnyArrowFunction()
{
    /** @var string[] $items */
    $items = doSomething();

    return array_any($items, fn ($item): bool => $item !== '');
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayAnyAllClosureParamTypeRector\Fixture;

function arrayAnyArrowFunction()
{
    /** @var string[] $items */
    $items = doSomething();

    return array_any($items, fn (string $item): bool => $item !== '');
}

?>
