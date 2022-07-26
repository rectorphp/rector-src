<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

use Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Source\SomeParentClass;

final class SkipNegationClassConstFetchFromParent extends SomeParentClass
{
    protected function run()
    {
        echo -static::CSRF_HASH_BYTES * 2;
    }
}
