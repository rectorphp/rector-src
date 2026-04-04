<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Countable;

final class SkipCountable implements Countable
{
    public function count(): int
    {
        return 0;
    }
}
