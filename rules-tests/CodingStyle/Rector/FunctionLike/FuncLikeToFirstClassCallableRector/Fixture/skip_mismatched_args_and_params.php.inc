<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

function ($foo, $bar)
{
    return FooBar::foo($bar, $foo);
};

fn ($foo, $bar) => FooBar::foo($bar, $foo);

$bar = null;

fn ($foo, $bar) => $bar->foo($bar, $foo);

function ($foo, $barFoo) use ($bar)
{
    return $bar->foo($barFoo, $foo);
};

?>
