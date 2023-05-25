<?php

namespace Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Fixture;

use Webmozart\Assert\Assert;

final class SkipAnotherStaticCall
{
    public function __construct($value)
    {
        Assert::string($value);
    }
}
