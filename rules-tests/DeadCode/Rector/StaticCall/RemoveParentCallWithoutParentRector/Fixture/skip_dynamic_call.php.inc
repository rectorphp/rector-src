<?php

namespace Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Fixture;

use Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Source\SomeParentMethod;

class SkipDynamicCallName extends SomeParentMethod
{
    public function innerSearchService(string $function, array $args): mixed
    {
        $result = parent::{$function}(...$args);
    }
}
