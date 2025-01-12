<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\Fixture;

function ($foo)
{
    return $foo;
};

fn ($foo) => $foo;

?>
