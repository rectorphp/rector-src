<?php

namespace Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Fixture;

use Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Source\SkipBrokenHierarchyParent;

class SkipBrokenHierarchy extends SkipBrokenHierarchyParent
{
    public function run()
    {
        parent::run();
    }
}
