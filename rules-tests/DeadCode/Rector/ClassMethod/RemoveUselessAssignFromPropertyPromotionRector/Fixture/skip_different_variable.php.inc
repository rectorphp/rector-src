<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotionRector\Fixture;

class SkipDifferentVariable
{
    public function __construct(private readonly \stdClass $std)
    {
        $differentVariable = new \stdClass();
        $this->std = $differentVariable;
    }
}

