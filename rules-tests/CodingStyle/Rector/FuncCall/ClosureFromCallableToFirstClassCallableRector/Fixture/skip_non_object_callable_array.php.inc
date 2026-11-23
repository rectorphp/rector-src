<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

use Closure;

function skipStringCallableArray(string $obj)
{
    return Closure::fromCallable([$obj, 'method']);
}

function skipUntypedCallableArray($obj)
{
    return Closure::fromCallable([$obj, 'method']);
}

?>
