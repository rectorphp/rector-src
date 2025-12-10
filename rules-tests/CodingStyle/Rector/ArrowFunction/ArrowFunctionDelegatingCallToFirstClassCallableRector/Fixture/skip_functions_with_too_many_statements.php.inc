<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

function ($foo)
{
    $foo = 1;
    return FooBar::foo($foo);
};

fn ($foo) => FooBar::foo($foo) && true;

$bar = null;

function ($foo) use ($bar)
{
    $foo = 1;
    return $bar->foo($foo);
};

?>
