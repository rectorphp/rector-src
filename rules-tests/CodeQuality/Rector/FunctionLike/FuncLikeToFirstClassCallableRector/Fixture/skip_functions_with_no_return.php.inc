<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

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
