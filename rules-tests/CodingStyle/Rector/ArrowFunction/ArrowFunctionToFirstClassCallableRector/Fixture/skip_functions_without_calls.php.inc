<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

function ($foo)
{
    return $foo;
};

fn ($foo) => $foo;

?>
