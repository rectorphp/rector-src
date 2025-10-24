<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Source\FooBar;

function ($foo)
{
    return FooBar::foo($foo);
};

fn ($foo) => FooBar::foo($foo);

function ($foo, $bar, $ray)
{
    return FooBar::foo($foo, $bar);
};

fn ($foo, $bar, $ray) => FooBar::foo($foo);

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Source\FooBar;

FooBar::foo(...);

FooBar::foo(...);

FooBar::foo(...);

FooBar::foo(...);

?>
