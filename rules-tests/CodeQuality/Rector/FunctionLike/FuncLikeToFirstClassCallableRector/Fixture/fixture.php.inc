<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

function ($foo)
{
    return FooBar::foo($foo);
};

fn ($foo) => FooBar::foo($foo);

$bar = null;

fn ($foo) => $bar->foo($foo);

function ($foo) use ($bar)
{
    return $bar->foo($foo);
};

function ($foo, $bar, $ray)
{
    return FooBar::foo($foo, $bar);
};

fn ($foo, $bar, $ray) => FooBar::foo($foo);

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

FooBar::foo(...);

FooBar::foo(...);

$bar = null;

$bar->foo(...);

$bar->foo(...);

FooBar::foo(...);

FooBar::foo(...);

?>
