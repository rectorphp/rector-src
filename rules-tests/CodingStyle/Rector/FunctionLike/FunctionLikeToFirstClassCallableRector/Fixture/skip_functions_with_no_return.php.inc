<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

function ($foo)
{
    FooBar::foo($foo);
};

$bar = null;

function ($foo) use ($bar)
{
    $bar->foo($foo);
};

?>
