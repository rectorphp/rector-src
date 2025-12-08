<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

function ($foo, $bar)
{
    return FooBar::foo($bar, $foo);
};

$bar = null;

function ($foo, $barFoo) use ($bar)
{
    return $bar->foo($barFoo, $foo);
};
