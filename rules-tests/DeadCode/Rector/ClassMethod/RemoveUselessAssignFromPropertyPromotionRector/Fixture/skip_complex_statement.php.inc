<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotionRector\Fixture;

class SkipComplexStatement
{
    public function __construct(private \stdClass $std)
    {
        if (rand(0, 1)) {
            $this->std = '';
        }
    }
}

