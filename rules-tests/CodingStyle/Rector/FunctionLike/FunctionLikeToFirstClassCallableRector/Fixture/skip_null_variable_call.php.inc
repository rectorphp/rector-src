<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

$bar = null;

fn ($foo) => $bar->foo($foo);

function ($foo) use ($bar)
{
    return $bar->foo($foo);
};