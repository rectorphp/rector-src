<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayAnyAllClosureParamTypeRector\Fixture;

function skipAlreadyTyped()
{
    /** @var string[] $items */
    $items = doSomething();

    return array_any($items, fn (string $item): bool => $item !== '');
}
