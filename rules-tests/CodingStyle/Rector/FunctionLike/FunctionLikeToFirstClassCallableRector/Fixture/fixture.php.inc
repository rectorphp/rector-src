<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

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

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

FooBar::foo(...);

FooBar::foo(...);

$bar = null;

$bar->foo(...);

$bar->foo(...);

FooBar::foo(...);

FooBar::foo(...);

?>
