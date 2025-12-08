<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

$bar = null;

fn ($foo) => $bar->foo($foo);

function ($foo) use ($bar)
{
    return $bar->foo($foo);
};
