<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

$bar = null;

function ($foo) use ($bar)
{
    return $bar->foo(foo: $foo);
};

function ($foo)
{
    return Foo::foo(foo: $foo);
};

?>
