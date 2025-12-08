<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

$bar = null;

function ($foo) use ($bar)
{
    return $bar->foo($foo);
};
