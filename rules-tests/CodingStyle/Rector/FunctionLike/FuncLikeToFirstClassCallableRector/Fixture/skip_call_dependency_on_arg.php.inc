<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

function ($foo)
{
    return $foo->foo();
};

function ($foo)
{
    return (new $foo)->foo();
};

fn ($foo) => $foo->foo();

function ($foo)
{
    return $foo::foo();
};

function ($foo)
{
    return ($foo . '\\Foo')::foo();
};

?>
