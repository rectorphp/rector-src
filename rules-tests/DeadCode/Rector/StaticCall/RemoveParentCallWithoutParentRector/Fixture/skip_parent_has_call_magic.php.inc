<?php

namespace Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Fixture;

use Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\Source\SomeParentMethodMagicCall;

class SkipParentHasCallMagic extends SomeParentMethodMagicCall
{
    public function getDerp() {
        $derp = parent::getDerp();
    }
}

