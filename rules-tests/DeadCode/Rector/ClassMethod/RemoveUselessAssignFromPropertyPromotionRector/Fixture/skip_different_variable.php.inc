<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotionRector\Fixture;

use stdClass;

class SkipDifferentVariable
{
    public function __construct(private \stdClass $std, stdClass $std2)
    {
        $this->std = $std2;
    }
}

