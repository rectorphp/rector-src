<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

function ($foo)
{
    return $foo->foo();
};

fn ($foo) => $foo->foo();

?>
