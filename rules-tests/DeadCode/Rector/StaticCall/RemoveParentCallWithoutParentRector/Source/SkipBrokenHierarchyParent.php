<?php

namespace Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Source;

// the grandparent class is not autoloadable, so the ancestor chain cannot be
// fully resolved and the called method cannot be safely declared as missing
abstract class SkipBrokenHierarchyParent extends NonAutoloadableGrandParent
{
}
