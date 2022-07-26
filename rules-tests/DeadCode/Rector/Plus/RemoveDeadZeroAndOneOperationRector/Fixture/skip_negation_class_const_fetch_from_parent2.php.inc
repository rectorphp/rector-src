<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

use Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Source\SomeParentClass;

final class SkipNegationClassConstFetchFromParent2 extends SomeParentClass
{
    protected function run()
    {
        echo -parent::CSRF_HASH_BYTES * 2;
    }
}
