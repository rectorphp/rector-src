<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

function ($foo)
{
    return FooBar::foo()->bar($foo);
};

fn ($foo) => FooBar::foo()->bar($foo);
